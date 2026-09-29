<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ $title ?? 'Pendaftaran Pelamar — ' . config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        {{-- Tailwind (Vite) dimuat lebih dulu, lalu CSS Filament sesudahnya
             agar preflight Tailwind tidak menimpa styling komponen Filament. --}}
        @vite('resources/css/app.css')
        @filamentStyles

        <style>
            /* Fallback: pastikan input/select/textarea selalu terlihat batasnya */
            .fi-input,
            .fi-select,
            .fi-textarea,
            input.fi-input,
            select.fi-select,
            textarea.fi-textarea {
                border-width: 1px;
                border-style: solid;
                border-color: #d1d5db;
                background-color: #ffffff;
                border-radius: 0.5rem;
                padding: 0.5rem 0.75rem;
            }

            /* Dropdown select: hilangkan efek tembus/transparan */
            .fi-select option,
            select.fi-select option {
                background-color: #ffffff;
                color: #111827;
            }

            /* Samakan tampilan dropdown bawaan browser bila native select dipakai */
            select.fi-select {
                appearance: none;
                -webkit-appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
                padding-right: 2.5rem;
            }
        </style>
    </head>

    <body class="antialiased">
        {{ $slot }}

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
