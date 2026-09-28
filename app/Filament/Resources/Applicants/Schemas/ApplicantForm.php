<?php

namespace App\Filament\Resources\Applicants\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApplicantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama & Posisi')
                    ->schema([
                        TextInput::make('full_name')
                            ->required()
                            ->label('Nama Lengkap'),
                        TextInput::make('position_applied')
                            ->label('Posisi yang Dilamar'),
                        TextInput::make('expected_salary')
                            ->numeric()
                            ->label('Gaji yang Diinginkan (Rp)'),
                        TextInput::make('estimated_living_cost')
                            ->numeric()
                            ->label('Perkiraan Biaya Hidup (Rp)'),
                        DatePicker::make('available_start_date')
                            ->label('Mulai Bekerja Tanggal'),
                    ])->columns(2),

                Section::make('Riwayat Pengalaman Kerja')
                    ->schema([
                        Repeater::make('workExperiences')
                            ->relationship()
                            ->schema([
                                TextInput::make('company_name')->label('Nama Perusahaan'),
                                TextInput::make('last_position')->label('Jabatan Terakhir'),
                                TextInput::make('duration')->label('Lama Bekerja'),
                                TextInput::make('last_salary')->numeric()->label('Gaji Terakhir'),
                                TextInput::make('reason_for_moving')->label('Alasan Pindah'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengalaman Organisasi')
                    ->schema([
                        Repeater::make('organizations')
                            ->relationship()
                            ->schema([
                                TextInput::make('organization_name')->label('Nama Organisasi'),
                                TextInput::make('position')->label('Jabatan'),
                                TextInput::make('year')->label('Tahun'),
                                TextInput::make('city')->label('Kota'),
                                TextInput::make('achievement')->label('Prestasi/Keterangan'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Riwayat Penyakit / Kecelakaan Berat')
                    ->schema([
                        Repeater::make('diseases')
                            ->relationship()
                            ->schema([
                                TextInput::make('disease_or_accident')->label('Jenis Penyakit/Kecelakaan'),
                                TextInput::make('treatment_year')->label('Tahun Perawatan'),
                                TextInput::make('duration')->label('Lamanya Sakit'),
                                TextInput::make('lasting_impact')->label('Dampak/Kondisi Saat Ini'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Referensi (Orang yang Mengenal Anda)')
                    ->schema([
                        Repeater::make('references')
                            ->relationship()
                            ->schema([
                                TextInput::make('name')->label('Nama Lengkap'),
                                TextInput::make('address_or_office')->label('Alamat / Kantor'),
                                TextInput::make('phone')->label('No. Telepon'),
                                TextInput::make('relationship')->label('Hubungan'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Kenalan di Dalam Perusahaan Ini')
                    ->schema([
                        Repeater::make('internalConnections')
                            ->relationship()
                            ->schema([
                                TextInput::make('name')->label('Nama Kenalan'),
                                TextInput::make('position_and_department')->label('Jabatan & Bagian'),
                                TextInput::make('relationship')->label('Hubungan'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Riwayat Psikotes / Test Lainnya')
                    ->schema([
                        Repeater::make('psychoTests')
                            ->relationship()
                            ->schema([
                                TextInput::make('institution_name')->label('Nama Lembaga/Instansi'),
                                TextInput::make('year')->label('Tahun'),
                                TextInput::make('city')->label('Kota'),
                                TextInput::make('purpose')->label('Keperluan/Tujuan'),
                            ])
                            ->columns(2)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pertanyaan & Essay Lainnya')
                    ->schema([
                        Textarea::make('financial_problem_history')
                            ->label('Pernah mengalami masalah keuangan berat? Jelaskan:')
                            ->columnSpanFull(),
                        Textarea::make('important_problem_history')
                            ->label('Masalah penting/kritis yang pernah dihadapi & cara mengatasinya:')
                            ->columnSpanFull(),
                        Textarea::make('strengths')
                            ->label('Kelebihan / Potensi Diri Anda:')
                            ->columnSpanFull(),
                        Textarea::make('weaknesses')
                            ->label('Kelemahan / Hal yang Perlu Diperbaiki:')
                            ->columnSpanFull(),
                        Textarea::make('three_year_plan')
                            ->label('Rencana & Target Anda 3 Tahun ke Depan:')
                            ->columnSpanFull(),
                    ]),

                Section::make('Pernyataan & Persetujuan')
                    ->schema([
                        Checkbox::make('agree_contract_system')->label('Bersedia bekerja sistem kontrak'),
                        Checkbox::make('agree_shift_work')->label('Bersedia bekerja shift'),
                        Checkbox::make('agree_overtime')->label('Bersedia lembur'),
                        Checkbox::make('agree_umk_salary')->label('Bersedia gaji sesuai UMK'),
                        Checkbox::make('agree_company_vision')->label('Menerima Visi & Misi Perusahaan'),
                    ])->columns(2),
            ]);
    }
}
