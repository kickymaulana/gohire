<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Pengalaman Kerja
        Schema::create('applicant_work_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->string('company_name');
            $table->string('last_position');
            $table->string('duration'); // Contoh: 2 Tahun
            $table->decimal('last_salary', 12, 2)->nullable();
            $table->string('reason_for_moving')->nullable();
            $table->timestamps();
        });

        // 2. Organisasi
        Schema::create('applicant_organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->string('organization_name');
            $table->string('position');
            $table->string('year');
            $table->string('city');
            $table->string('achievement')->nullable();
            $table->timestamps();
        });

        // 3. Riwayat Penyakit / Kecelakaan
        Schema::create('applicant_diseases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->string('disease_or_accident');
            $table->string('treatment_year');
            $table->string('duration');
            $table->string('lasting_impact')->nullable();
            $table->timestamps();
        });

        // 4. Referensi
        Schema::create('applicant_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->string('name');
            $table->string('address_or_office');
            $table->string('phone');
            $table->string('relationship');
            $table->timestamps();
        });

        // 5. Kenalan di Dalam Perusahaan
        Schema::create('applicant_internal_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->string('name');
            $table->string('position_and_department');
            $table->string('relationship');
            $table->timestamps();
        });

        // 6. Riwayat Psikotes
        Schema::create('applicant_psycho_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained('applicants')->onDelete('cascade');
            $table->string('institution_name');
            $table->string('year');
            $table->string('city');
            $table->string('purpose');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_psycho_tests');
        Schema::dropIfExists('applicant_internal_connections');
        Schema::dropIfExists('applicant_references');
        Schema::dropIfExists('applicant_diseases');
        Schema::dropIfExists('applicant_organizations');
        Schema::dropIfExists('applicant_work_experiences');
    }
};
