<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom data diri pelamar yang diminta atasan:
     * alamat, no HP/WA aktif, tanggal lahir, tinggi badan, berat badan, tamatan.
     */
    public function up(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->text('address')->nullable()->after('full_name');
            $table->string('phone_whatsapp', 30)->nullable()->after('address');
            $table->date('birth_date')->nullable()->after('phone_whatsapp');
            $table->unsignedSmallInteger('height_cm')->nullable()->after('birth_date');
            $table->unsignedSmallInteger('weight_kg')->nullable()->after('height_cm');
            $table->string('education_level', 50)->nullable()->after('weight_kg');
        });
    }

    public function down(): void
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'phone_whatsapp',
                'birth_date',
                'height_cm',
                'weight_kg',
                'education_level',
            ]);
        });
    }
};
