<?php

namespace App\Livewire;

use App\Models\Applicant;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class PublicRegistrationForm extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public bool $submitted = false;

    public ?string $errorMessage = null;

    public int $step = 1;

    public int $totalSteps = 5;

    public array $stepLabels = [
        1 => 'Data Diri',
        2 => 'Pengalaman & Organisasi',
        3 => 'Referensi & Kenalan',
        4 => 'Kesehatan & Psikotes',
        5 => 'Essay & Pernyataan',
    ];

    public function mount(): void
    {
        $this->form->fill([
            'has_disease_history' => false,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // LANGKAH 1: Data Diri
                Section::make('Langkah 1 dari 5 — Data Diri')
                    ->description('Isi nama lengkap dan posisi yang Anda lamar. Field bertanda * wajib diisi.')
                    ->schema([
                        TextInput::make('full_name')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Lengkap'),
                        TextInput::make('position_applied')
                            ->required()
                            ->maxLength(255)
                            ->label('Posisi yang Dilamar'),
                        TextInput::make('expected_salary')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->maxValue(1000000000)
                            ->helperText('Maksimal Rp 1.000.000.000 (1 miliar).')
                            ->label('Gaji yang Diinginkan (opsional)'),
                        TextInput::make('estimated_living_cost')
                            ->numeric()
                            ->prefix('Rp')
                            ->minValue(0)
                            ->maxValue(1000000000)
                            ->helperText('Maksimal Rp 1.000.000.000 (1 miliar).')
                            ->label('Perkiraan Biaya Hidup (opsional)'),
                        DatePicker::make('available_start_date')
                            ->label('Mulai Bekerja Tanggal (opsional)'),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $this->step === 1),

                // LANGKAH 2: Pengalaman & Organisasi
                Section::make('Langkah 2 dari 5 — Pengalaman Kerja')
                    ->description('Bagian ini opsional. Kosongkan jika Anda belum pernah bekerja.')
                    ->schema([
                        Repeater::make('workExperiences')
                            ->label('Riwayat Pekerjaan')
                            ->schema([
                                TextInput::make('company_name')->label('Nama Perusahaan'),
                                TextInput::make('last_position')->label('Jabatan Terakhir'),
                                TextInput::make('duration')->label('Lama Bekerja'),
                                TextInput::make('last_salary')->numeric()->label('Gaji Terakhir'),
                                TextInput::make('reason_for_moving')->label('Alasan Pindah'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Pengalaman Kerja')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $this->step === 2),

                Section::make('Pengalaman Organisasi')
                    ->description('Opsional. Kosongkan jika tidak ada.')
                    ->schema([
                        Repeater::make('organizations')
                            ->label('Organisasi')
                            ->schema([
                                TextInput::make('organization_name')->label('Nama Organisasi'),
                                TextInput::make('position')->label('Jabatan'),
                                TextInput::make('year')->label('Tahun'),
                                TextInput::make('city')->label('Kota'),
                                TextInput::make('achievement')->label('Prestasi/Keterangan'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Organisasi')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $this->step === 2),

                // LANGKAH 3: Referensi & Kenalan
                Section::make('Langkah 3 dari 5 — Referensi')
                    ->description('Opsional. Sebutkan orang yang mengenal Anda.')
                    ->schema([
                        Repeater::make('references')
                            ->label('Referensi')
                            ->schema([
                                TextInput::make('name')->label('Nama Lengkap'),
                                TextInput::make('address_or_office')->label('Alamat / Kantor'),
                                TextInput::make('phone')->label('No. Telepon'),
                                TextInput::make('relationship')->label('Hubungan'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Referensi')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $this->step === 3),

                Section::make('Kenalan di Dalam Perusahaan Ini')
                    ->description('Opsional. Kosongkan jika tidak ada.')
                    ->schema([
                        Repeater::make('internalConnections')
                            ->label('Kenalan di Perusahaan')
                            ->schema([
                                TextInput::make('name')->label('Nama Kenalan'),
                                TextInput::make('position_and_department')->label('Jabatan & Bagian'),
                                TextInput::make('relationship')->label('Hubungan'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Kenalan')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $this->step === 3),

                // LANGKAH 4: Kesehatan & Psikotes
                Section::make('Langkah 4 dari 5 — Kesehatan')
                    ->description('Bagian ini opsional dan bersifat rahasia. Silakan lewati bila Anda tidak berkenan menyebutkannya.')
                    ->schema([
                        Placeholder::make('privacy_notice')
                            ->label('Catatan Privasi')
                            ->content(new HtmlString(
                                '<div class="rounded-lg border border-blue-200 bg-blue-50 p-3 text-sm text-blue-800">'.
                                'Data kesehatan termasuk <strong>data pribadi spesifik</strong> yang dilindungi UU No. 27/2022 tentang Pelindungan Data Pribadi. '.
                                'Anda <strong>berhak tidak mengisi</strong> bagian ini tanpa memengaruhi penilaian lamaran Anda.'.
                                '</div>'
                            ))
                            ->columnSpanFull(),
                        Checkbox::make('has_disease_history')
                            ->label('Saya memiliki riwayat penyakit berat / kecelakaan yang ingin saya sampaikan')
                            ->helperText('Centang hanya bila Anda bersedia menyebutkannya.')
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state): void {
                                if (! $state) {
                                    $set('diseases', []);
                                }
                            })
                            ->columnSpanFull(),
                        Repeater::make('diseases')
                            ->label('Riwayat Penyakit')
                            ->schema([
                                TextInput::make('disease_or_accident')->label('Jenis Penyakit/Kecelakaan'),
                                TextInput::make('treatment_year')->label('Tahun Perawatan'),
                                TextInput::make('duration')->label('Lamanya Sakit'),
                                TextInput::make('lasting_impact')->label('Dampak/Kondisi Saat Ini'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Riwayat Penyakit')
                            ->columnSpanFull()
                            ->visible(fn (Get $get): bool => (bool) $get('has_disease_history')),
                    ])
                    ->visible(fn (Get $get): bool => $this->step === 4),

                Section::make('Riwayat Psikotes / Test Lainnya')
                    ->description('Opsional. Kosongkan jika tidak pernah mengikuti psikotes.')
                    ->schema([
                        Repeater::make('psychoTests')
                            ->label('Riwayat Psikotes')
                            ->schema([
                                TextInput::make('institution_name')->label('Nama Lembaga/Instansi'),
                                TextInput::make('year')->label('Tahun'),
                                TextInput::make('city')->label('Kota'),
                                TextInput::make('purpose')->label('Keperluan/Tujuan'),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Riwayat Psikotes')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $this->step === 4),

                // LANGKAH 5: Essay & Pernyataan
                Section::make('Langkah 5 dari 5 — Pertanyaan & Pernyataan')
                    ->description('Bagian terakhir. Semua pertanyaan essay bersifat opsional.')
                    ->schema([
                        Textarea::make('financial_problem_history')
                            ->label('Pernah mengalami masalah keuangan berat? Jelaskan (opsional)')
                            ->columnSpanFull(),
                        Textarea::make('important_problem_history')
                            ->label('Masalah penting/kritis yang pernah dihadapi & cara mengatasinya (opsional)')
                            ->columnSpanFull(),
                        Textarea::make('strengths')
                            ->label('Kelebihan / Potensi Diri Anda (opsional)')
                            ->columnSpanFull(),
                        Textarea::make('weaknesses')
                            ->label('Kelemahan / Hal yang Perlu Diperbaiki (opsional)')
                            ->columnSpanFull(),
                        Textarea::make('three_year_plan')
                            ->label('Rencana & Target Anda 3 Tahun ke Depan (opsional)')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Get $get): bool => $this->step === 5),

                Section::make('Pernyataan & Persetujuan')
                    ->description('Centang pernyataan yang Anda setujui. Boleh tidak mencentang bila tidak bersedia.')
                    ->schema([
                        Checkbox::make('agree_contract_system')
                            ->label('Bersedia bekerja sistem kontrak'),
                        Checkbox::make('agree_shift_work')
                            ->label('Bersedia bekerja shift'),
                        Checkbox::make('agree_overtime')
                            ->label('Bersedia lembur'),
                        Checkbox::make('agree_umk_salary')
                            ->label('Bersedia gaji sesuai UMK'),
                        Checkbox::make('agree_company_vision')
                            ->label('Menerima Visi & Misi Perusahaan'),
                    ])
                    ->columns(2)
                    ->visible(fn (Get $get): bool => $this->step === 5),
            ])
            ->statePath('data');
    }

    public function nextStep(): void
    {
        if (! $this->validateCurrentStep()) {
            return;
        }

        if ($this->step < $this->totalSteps) {
            $this->step++;
            $this->errorMessage = null;
        }
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step < 1 || $step > $this->totalSteps) {
            return;
        }

        // Maju hanya boleh bila langkah saat ini valid
        if ($step > $this->step && ! $this->validateCurrentStep()) {
            return;
        }

        $this->step = $step;
        $this->errorMessage = null;
    }

    protected function validateCurrentStep(): bool
    {
        $rules = [
            1 => [
                'full_name' => ['required', 'string', 'max:255'],
                'position_applied' => ['required', 'string', 'max:255'],
            ],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
        ];

        $stepRules = $rules[$this->step] ?? [];

        if (empty($stepRules)) {
            $this->errorMessage = null;

            return true;
        }

        $validator = Validator::make(
            $this->data ?? [],
            $stepRules,
            [
                'full_name.required' => 'Nama Lengkap wajib diisi.',
                'position_applied.required' => 'Posisi yang Dilamar wajib diisi.',
                'full_name.max' => 'Nama Lengkap maksimal 255 karakter.',
                'position_applied.max' => 'Posisi yang Dilamar maksimal 255 karakter.',
            ],
            [
                'full_name' => 'Nama Lengkap',
                'position_applied' => 'Posisi yang Dilamar',
            ]
        );

        if ($validator->fails()) {
            $this->errorMessage = implode(' ', $validator->errors()->all());

            return false;
        }

        $this->errorMessage = null;

        return true;
    }

    public function submit(): void
    {
        $this->validate([
            'data.full_name' => ['required', 'string', 'max:255'],
            'data.position_applied' => ['required', 'string', 'max:255'],
            'data.expected_salary' => ['nullable', 'numeric', 'min:0', 'max:1000000000'],
            'data.estimated_living_cost' => ['nullable', 'numeric', 'min:0', 'max:1000000000'],
        ]);

        $data = $this->data;

        DB::transaction(function () use ($data) {
            $applicant = Applicant::create([
                'full_name' => $data['full_name'],
                'position_applied' => $data['position_applied'] ?? null,
                'expected_salary' => $data['expected_salary'] ?? null,
                'estimated_living_cost' => $data['estimated_living_cost'] ?? null,
                'available_start_date' => $data['available_start_date'] ?? null,
                'financial_problem_history' => $data['financial_problem_history'] ?? null,
                'important_problem_history' => $data['important_problem_history'] ?? null,
                'strengths' => $data['strengths'] ?? null,
                'weaknesses' => $data['weaknesses'] ?? null,
                'three_year_plan' => $data['three_year_plan'] ?? null,
                'agree_contract_system' => $data['agree_contract_system'] ?? false,
                'agree_shift_work' => $data['agree_shift_work'] ?? false,
                'agree_overtime' => $data['agree_overtime'] ?? false,
                'agree_umk_salary' => $data['agree_umk_salary'] ?? false,
                'agree_company_vision' => $data['agree_company_vision'] ?? false,
                'signature_location' => 'Tanjung Morawa',
                'signature_date' => now(),
            ]);

            $relations = [
                'workExperiences' => ['company_name', 'last_position', 'duration', 'last_salary', 'reason_for_moving'],
                'organizations' => ['organization_name', 'position', 'year', 'city', 'achievement'],
                'diseases' => ['disease_or_accident', 'treatment_year', 'duration', 'lasting_impact'],
                'references' => ['name', 'address_or_office', 'phone', 'relationship'],
                'internalConnections' => ['name', 'position_and_department', 'relationship'],
                'psychoTests' => ['institution_name', 'year', 'city', 'purpose'],
            ];

            foreach ($relations as $key => $fields) {
                foreach ($data[$key] ?? [] as $row) {
                    // Lewati baris yang seluruh field-nya kosong
                    $record = array_filter(
                        array_intersect_key($row, array_flip($fields)),
                        fn ($value) => filled($value)
                    );

                    if (! empty($record)) {
                        $applicant->{$key}()->create($record);
                    }
                }
            }
        });

        $this->form->fill(['has_disease_history' => false]);
        $this->step = 1;
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.public-registration-form')
            ->layout('layouts.app');
    }
}
