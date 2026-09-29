<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Memperbesar kolom gaji dari decimal(12,2) menjadi decimal(15,2)
     * agar menampung nilai di atas 9,9 miliar.
     */
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->decimal('expected_salary', 15, 2)->nullable()->change();
            $table->decimal('estimated_living_cost', 15, 2)->nullable()->change();
        });

        Schema::table('applicant_work_experiences', function (Blueprint $table) {
            $table->decimal('last_salary', 15, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->decimal('expected_salary', 12, 2)->nullable()->change();
            $table->decimal('estimated_living_cost', 12, 2)->nullable()->change();
        });

        Schema::table('applicant_work_experiences', function (Blueprint $table) {
            $table->decimal('last_salary', 12, 2)->nullable()->change();
        });
    }
};
