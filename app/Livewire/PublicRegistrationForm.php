<?php

namespace App\Livewire;

use App\Models\Applicant;
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
                        TextInput::make('phone_whatsapp')
                            ->tel()
                            ->maxLength(30)
                            ->label('No HP / WhatsApp Aktif (opsional)'),
                        Select::make('education_level')
                            ->label('Tamatan / Pendidikan Terakhir (opsional)')
                            ->options([
                                'SD' => 'SD',
                                'SMP' => 'SMP / Sederajat',
                                'SMA' => 'SMA / SMK / Sederajat',
                                'D1' => 'D1',
                                'D2' => 'D2',
                                'D3' => 'D3',
                                'D4' => 'D4',
                                'S1' => 'S1',
                                'S2' => 'S2',
                                'S3' => 'S3',
                            ])
                            ->native(false),
                        TextInput::make('birth_date')
                            ->placeholder('Contoh: 15/05/1998')
                            ->label('Tanggal Lahir (opsional)'),
                        TextInput::make('height_cm')
                            ->suffix('cm')
                            ->label('Tinggi Badan (opsional)'),
                        TextInput::make('weight_kg')
                            ->suffix('kg')
                            ->label('Berat Badan (opsional)'),
                        Textarea::make('address')
                            ->rows(2)
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->label('Alamat Lengkap (opsional)'),
                        TextInput::make('position_applied')
                            ->required()
                            ->maxLength(255)
                            ->label('Posisi yang Dilamar'),
                        TextInput::make('expected_salary')
                            ->prefix('Rp')
                            ->helperText('Contoh: 5.000.000 atau 5000000. Maksimal Rp 1.000.000.000.')
                            ->label('Gaji yang Diinginkan (opsional)'),
                        TextInput::make('estimated_living_cost')
                            ->prefix('Rp')
                            ->helperText('Contoh: 2.500.000 atau 2500000. Maksimal Rp 1.000.000.000.')
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
                                TextInput::make('last_salary')->label('Gaji Terakhir'),
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

    /**
     * Bersihkan input angka dari format ribuan Indonesia.
     * Pelamar di lapangan sering mengetik "5.000.000", "70,5", atau "Rp 5.000.000".
     */
    private function normalizeNumeric(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = trim((string) $value);

        // Format Indonesia: 1.234.567 atau 1.234.567,89 → 1234567 / 1234567.89
        if (preg_match('/^-?\d{1,3}(\.\d{3})+(,\d+)?$/', $value)) {
            return (float) str_replace(',', '.', str_replace('.', '', $value));
        }

        // Desimal berkoma: 70,5 → 70.5
        if (preg_match('/^-?\d+,\d+$/', $value)) {
            return (float) str_replace(',', '.', $value);
        }

        // Ribuan berkoma (format Inggris): 5,000,000 → 5000000
        if (preg_match('/^-?\d{1,3}(,\d{3})+$/', $value)) {
            return (float) str_replace(',', '', $value);
        }

        // Format desimal berkoma tunggal yang sebenarnya dimaksud sebagai desimal
        // dalam konteks Indonesia (mis. "65.5" tidak terdeteksi sebagai ribuan).
        if (preg_match('/^-?\d+\.\d+$/', $value)) {
            return (float) $value;
        }

        // Usaha terakhir: ambil digit saja
        $cleaned = preg_replace('/[^\d]/', '', $value);

        if ($cleaned === '') {
            return null;
        }

        $number = (float) $cleaned;

        // Tolak nilai tak masuk akal (melebihi kapasitas kolom decimal(15,2))
        return $number > 999999999999 ? null : $number;
    }

    /**
     * Normalisasi tanggal agar formatnya selalu Y-m-d.
     * Menangani format Indonesia: 15/05/1998 atau 15-05-1998.
     */
    private function normalizeDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = trim((string) $value);

        // Format Indonesia: dd/mm/yyyy atau dd-mm-yyyy
        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', $value, $m)) {
            $day = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($m[2], 2, '0', STR_PAD_LEFT);

            return "{$m[3]}-{$month}-{$day}";
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function submit(): void
    {
        $this->validate([
            'data.full_name' => ['required', 'string', 'max:255'],
            'data.position_applied' => ['required', 'string', 'max:255'],
            'data.phone_whatsapp' => ['nullable', 'string', 'max:30'],
        ]);

        $data = $this->data;

        // Normalisasi angka & tanggal sebelum disimpan ke database.
        // Dilakukan sebelum validasi numeric karena input pelamar bisa berformat
        // "5.000.000", "70,5", atau "Rp 2.500.000".
        $data['expected_salary'] = $this->normalizeNumeric($data['expected_salary'] ?? null);
        $data['estimated_living_cost'] = $this->normalizeNumeric($data['estimated_living_cost'] ?? null);
        $data['height_cm'] = $this->normalizeNumeric($data['height_cm'] ?? null);
        $data['weight_kg'] = $this->normalizeNumeric($data['weight_kg'] ?? null);
        $data['birth_date'] = $this->normalizeDate($data['birth_date'] ?? null);
        $data['available_start_date'] = $this->normalizeDate($data['available_start_date'] ?? null);

        // Validasi batas nilai dilakukan SETELAH normalisasi.
        // errorMessage global dipakai karena user berada di langkah 5 saat submit,
        // sedangkan field yang error ada di langkah 1 (tidak terlihat).
        if ($data['expected_salary'] !== null && $data['expected_salary'] > 1000000000) {
            $this->addError('data.expected_salary', 'Gaji yang diinginkan maksimal Rp 1.000.000.000.');
            $this->errorMessage = 'Gaji yang diinginkan maksimal Rp 1.000.000.000.';

            return;
        }

        if ($data['estimated_living_cost'] !== null && $data['estimated_living_cost'] > 1000000000) {
            $this->addError('data.estimated_living_cost', 'Perkiraan biaya hidup maksimal Rp 1.000.000.000.');
            $this->errorMessage = 'Perkiraan biaya hidup maksimal Rp 1.000.000.000.';

            return;
        }

        if ($data['height_cm'] !== null && ($data['height_cm'] < 0 || $data['height_cm'] > 300)) {
            $this->addError('data.height_cm', 'Tinggi badan harus antara 0-300 cm.');
            $this->errorMessage = 'Tinggi badan harus antara 0-300 cm.';

            return;
        }

        if ($data['weight_kg'] !== null && ($data['weight_kg'] < 0 || $data['weight_kg'] > 500)) {
            $this->addError('data.weight_kg', 'Berat badan harus antara 0-500 kg.');
            $this->errorMessage = 'Berat badan harus antara 0-500 kg.';

            return;
        }

        if ($data['birth_date'] && $data['birth_date'] > now()->format('Y-m-d')) {
            $this->addError('data.birth_date', 'Tanggal lahir tidak boleh di masa depan.');
            $this->errorMessage = 'Tanggal lahir tidak boleh di masa depan.';

            return;
        }

        DB::transaction(function () use ($data) {
            $applicant = Applicant::create([
                'full_name' => $data['full_name'],
                'address' => $data['address'] ?? null,
                'phone_whatsapp' => $data['phone_whatsapp'] ?? null,
                'birth_date' => $data['birth_date'] ?? null,
                'height_cm' => $data['height_cm'] ?? null,
                'weight_kg' => $data['weight_kg'] ?? null,
                'education_level' => $data['education_level'] ?? null,
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

            // Kolom yang perlu dibersihkan format ribuannya per relasi
            $numericFields = [
                'workExperiences' => ['last_salary'],
            ];

            foreach ($relations as $key => $fields) {
                foreach ($data[$key] ?? [] as $row) {
                    // Lewati baris yang seluruh field-nya kosong
                    $record = array_filter(
                        array_intersect_key($row, array_flip($fields)),
                        fn ($value) => filled($value)
                    );

                    if (! empty($record)) {
                        foreach ($numericFields[$key] ?? [] as $numericField) {
                            if (isset($record[$numericField])) {
                                $record[$numericField] = $this->normalizeNumeric($record[$numericField]);
                            }
                        }

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
