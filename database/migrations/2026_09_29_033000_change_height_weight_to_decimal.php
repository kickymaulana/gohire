<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tinggi dan berat badan bisa memakai desimal (mis. 170,5 cm atau 65,5 kg),
     * sedangkan kolom awalnya unsignedSmallInteger (bilangan bulat) sehingga
     * nilai desimal terbuang.
     */
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->decimal('height_cm', 5, 1)->nullable()->change();
            $table->decimal('weight_kg', 5, 1)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->unsignedSmallInteger('height_cm')->nullable()->change();
            $table->unsignedSmallInteger('weight_kg')->nullable()->change();
        });
    }
};
