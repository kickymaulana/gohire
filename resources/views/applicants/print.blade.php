<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lamaran {{ $applicant->full_name }}</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.45; margin: 32px auto; max-width: 900px; }
        h2 { border-bottom: 1px solid #9ca3af; font-size: 15px; margin: 24px 0 8px; padding-bottom: 4px; }
        h3 { font-size: 13px; margin: 16px 0 6px; }
        p { margin: 0 0 8px; white-space: pre-line; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #9ca3af; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; }
        .document-header { border: 1px solid #111827; margin-bottom: 18px; }
        .document-header td { border: 0; padding: 8px 10px; }
        .document-logo { height: 54px; max-width: 180px; }
        .document-title { font-size: 18px; font-weight: 700; text-align: center; }
        .document-meta { border-top: 1px solid #111827; font-size: 10px; text-align: center; }
        .document-meta td { border-right: 1px solid #111827; }
        .document-meta td:last-child { border-right: 0; }
        .document-meta strong { display: block; font-size: 10px; font-weight: 400; }
        .document-meta span { display: block; font-size: 11px; font-weight: 700; margin-top: 3px; }
        @page { margin: 18mm 14mm 16mm; }
        @media print { body { margin: 0; max-width: none; } }
    </style>
</head>
<body>
    <table class="document-header">
        <tr>
            <td style="width: 24%;">
                @if ($logo)
                    <img class="document-logo" src="{{ $logo }}" alt="Mark Dynamics Indonesia">
                @else
                    <strong>MARK DYNAMICS INDONESIA</strong>
                @endif
            </td>
            <td class="document-title">FORMULIR PENERIMAAN KARYAWAN</td>
        </tr>
        <tr class="document-meta">
            <td><strong>No Dokumen</strong><span>MDFM-PER05</span></td>
            <td>
                <table>
                    <tr>
                        <td><strong>No Revisi</strong><span>02</span></td>
                        <td><strong>Tgl Berlaku</strong><span>26-Jul-13</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p>Diterima: {{ $applicant->created_at?->format('d/m/Y H:i') }}</p>

    <h2>Data Diri</h2>
    <table>
        <tr><th>Nama lengkap</th><td>{{ $applicant->full_name }}</td><th>No. HP / WhatsApp</th><td>{{ $applicant->phone_whatsapp ?: '-' }}</td></tr>
        <tr><th>Tanggal lahir</th><td>{{ $applicant->birth_date ? \Illuminate\Support\Carbon::parse($applicant->birth_date)->format('d/m/Y') : '-' }}</td><th>Pendidikan</th><td>{{ $applicant->education_level ?: '-' }}</td></tr>
        <tr><th>Tinggi / berat</th><td>{{ $applicant->height_cm ?: '-' }} cm / {{ $applicant->weight_kg ?: '-' }} kg</td><th>Posisi dilamar</th><td>{{ $applicant->position_applied ?: '-' }}</td></tr>
        <tr><th>Gaji diharapkan</th><td>{{ $applicant->expected_salary ? 'Rp '.number_format($applicant->expected_salary, 0, ',', '.') : '-' }}</td><th>Mulai bekerja</th><td>{{ $applicant->available_start_date ? \Illuminate\Support\Carbon::parse($applicant->available_start_date)->format('d/m/Y') : '-' }}</td></tr>
        <tr><th>Estimasi biaya hidup</th><td colspan="3">{{ $applicant->estimated_living_cost ? 'Rp '.number_format($applicant->estimated_living_cost, 0, ',', '.') : '-' }}</td></tr>
        <tr><th>Alamat</th><td colspan="3">{{ $applicant->address ?: '-' }}</td></tr>
    </table>

    <h2>Pengalaman Kerja</h2>
    <table><tr><th>Perusahaan</th><th>Posisi terakhir</th><th>Durasi</th><th>Gaji terakhir</th><th>Alasan pindah</th></tr>@forelse ($applicant->workExperiences as $item)<tr><td>{{ $item->company_name ?: '-' }}</td><td>{{ $item->last_position ?: '-' }}</td><td>{{ $item->duration ?: '-' }}</td><td>{{ $item->last_salary ? 'Rp '.number_format($item->last_salary, 0, ',', '.') : '-' }}</td><td>{{ $item->reason_for_moving ?: '-' }}</td></tr>@empty<tr><td colspan="5">Tidak ada data.</td></tr>@endforelse</table>

    <h2>Organisasi</h2>
    <table><tr><th>Organisasi</th><th>Jabatan</th><th>Tahun</th><th>Kota</th><th>Prestasi</th></tr>@forelse ($applicant->organizations as $item)<tr><td>{{ $item->organization_name ?: '-' }}</td><td>{{ $item->position ?: '-' }}</td><td>{{ $item->year ?: '-' }}</td><td>{{ $item->city ?: '-' }}</td><td>{{ $item->achievement ?: '-' }}</td></tr>@empty<tr><td colspan="5">Tidak ada data.</td></tr>@endforelse</table>

    <h2>Referensi</h2>
    <table><tr><th>Nama</th><th>Alamat / kantor</th><th>Telepon</th><th>Hubungan</th></tr>@forelse ($applicant->references as $item)<tr><td>{{ $item->name ?: '-' }}</td><td>{{ $item->address_or_office ?: '-' }}</td><td>{{ $item->phone ?: '-' }}</td><td>{{ $item->relationship ?: '-' }}</td></tr>@empty<tr><td colspan="4">Tidak ada data.</td></tr>@endforelse</table>

    <h2>Riwayat Kesehatan</h2>
    <table><tr><th>Penyakit / kecelakaan</th><th>Tahun pengobatan</th><th>Durasi</th><th>Dampak berkelanjutan</th></tr>@forelse ($applicant->diseases as $item)<tr><td>{{ $item->disease_or_accident ?: '-' }}</td><td>{{ $item->treatment_year ?: '-' }}</td><td>{{ $item->duration ?: '-' }}</td><td>{{ $item->lasting_impact ?: '-' }}</td></tr>@empty<tr><td colspan="4">Tidak ada data.</td></tr>@endforelse</table>

    <h2>Kenalan di Perusahaan</h2>
    <table><tr><th>Nama</th><th>Jabatan / departemen</th><th>Hubungan</th></tr>@forelse ($applicant->internalConnections as $item)<tr><td>{{ $item->name ?: '-' }}</td><td>{{ $item->position_and_department ?: '-' }}</td><td>{{ $item->relationship ?: '-' }}</td></tr>@empty<tr><td colspan="3">Tidak ada data.</td></tr>@endforelse</table>

    <h2>Riwayat Psikotes</h2>
    <table><tr><th>Institusi</th><th>Tahun</th><th>Kota</th><th>Tujuan</th></tr>@forelse ($applicant->psychoTests as $item)<tr><td>{{ $item->institution_name ?: '-' }}</td><td>{{ $item->year ?: '-' }}</td><td>{{ $item->city ?: '-' }}</td><td>{{ $item->purpose ?: '-' }}</td></tr>@empty<tr><td colspan="4">Tidak ada data.</td></tr>@endforelse</table>

    <h2>Essay dan Pernyataan</h2>
    <h3>Riwayat masalah keuangan</h3><p>{{ $applicant->financial_problem_history ?: '-' }}</p>
    <h3>Riwayat masalah penting</h3><p>{{ $applicant->important_problem_history ?: '-' }}</p>
    <h3>Kelebihan</h3><p>{{ $applicant->strengths ?: '-' }}</p>
    <h3>Kelemahan</h3><p>{{ $applicant->weaknesses ?: '-' }}</p>
    <h3>Rencana tiga tahun</h3><p>{{ $applicant->three_year_plan ?: '-' }}</p>
    <table><tr><th>Sistem kontrak</th><th>Kerja shift</th><th>Lembur</th><th>Gaji UMK</th><th>Visi perusahaan</th></tr><tr><td>{{ $applicant->agree_contract_system ? 'Setuju' : 'Tidak setuju' }}</td><td>{{ $applicant->agree_shift_work ? 'Setuju' : 'Tidak setuju' }}</td><td>{{ $applicant->agree_overtime ? 'Setuju' : 'Tidak setuju' }}</td><td>{{ $applicant->agree_umk_salary ? 'Setuju' : 'Tidak setuju' }}</td><td>{{ $applicant->agree_company_vision ? 'Setuju' : 'Tidak setuju' }}</td></tr></table>

    <p style="margin-top: 32px; text-align: right;">{{ $applicant->signature_location ?: '-' }}, {{ $applicant->signature_date ? \Illuminate\Support\Carbon::parse($applicant->signature_date)->format('d/m/Y') : '-' }}<br><br><br>{{ $applicant->full_name }}</p>
</body>
</html>
