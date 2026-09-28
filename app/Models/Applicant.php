<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
    protected $guarded = ['id'];

    // Relasi ke Pengalaman Kerja
    public function workExperiences(): HasMany
    {
        return $this->hasMany(ApplicantWorkExperience::class);
    }

    // Relasi ke Organisasi
    public function organizations(): HasMany
    {
        return $this->hasMany(ApplicantOrganization::class);
    }

    // Relasi ke Riwayat Penyakit
    public function diseases(): HasMany
    {
        return $this->hasMany(ApplicantDisease::class);
    }

    // Relasi ke Referensi
    public function references(): HasMany
    {
        return $this->hasMany(ApplicantReference::class);
    }

    // Relasi ke Kenalan di Perusahaan
    public function internalConnections(): HasMany
    {
        return $this->hasMany(ApplicantInternalConnection::class);
    }

    // Relasi ke Riwayat Psikotes
    public function psychoTests(): HasMany
    {
        return $this->hasMany(ApplicantPsychoTest::class);
    }
}
