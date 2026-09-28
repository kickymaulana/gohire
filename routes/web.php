<?php

use App\Livewire\PublicRegistrationForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('register');
});

// Halaman pendaftaran pelamar untuk masyarakat umum
Route::get('/register', PublicRegistrationForm::class)->name('register');
