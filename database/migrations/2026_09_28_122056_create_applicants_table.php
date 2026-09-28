<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();

            // Informasi Pelamar & Posisi
            $table->string('full_name');
            $table->string('position_applied')->nullable();
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->decimal('estimated_living_cost', 12, 2)->nullable();
            $table->date('available_start_date')->nullable();

            // Bagian Lain-lain (Essay)
            $table->text('financial_problem_history')->nullable();
            $table->text('important_problem_history')->nullable();

            // Kelebihan, Kelemahan, Rencana
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('three_year_plan')->nullable();

            // Checklist Persetujuan
            $table->boolean('agree_contract_system')->default(false);
            $table->boolean('agree_shift_work')->default(false);
            $table->boolean('agree_overtime')->default(false);
            $table->boolean('agree_umk_salary')->default(false);
            $table->boolean('agree_company_vision')->default(false);

            $table->text('other_notes')->nullable();
            $table->string('signature_location')->default('Tanjung Morawa');
            $table->date('signature_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
