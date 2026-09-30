<?php

use App\Http\Controllers\ApplicantPrintController;
use App\Livewire\PublicRegistrationForm;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('register');
});

// Halaman pendaftaran pelamar untuk masyarakat umum
Route::get('/register', PublicRegistrationForm::class)->name('register');

Route::redirect('/login', '/admin/login')->name('login');

Route::get('/admin/applicants/{applicant}/print', ApplicantPrintController::class)
    ->middleware('auth')
    ->name('applicants.print');
