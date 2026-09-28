<div class="min-h-screen bg-gray-50 py-10">
    <div class="mx-auto max-w-4xl px-4">
        {{-- Header --}}
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500 shadow-lg">
                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                Formulir Pendaftaran Pelamar
            </h1>
            <p class="mx-auto mt-3 max-w-xl text-base text-gray-600">
                Diisi bertahap. Field bertanda <span class="font-semibold text-rose-600">*</span> wajib diisi, sisanya boleh dikosongkan.
            </p>
        </div>

        @if ($submitted)
            {{-- Sukses --}}
            <div class="mx-auto max-w-2xl">
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-8 text-center shadow-sm">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                        <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-emerald-900">Pendaftaran Berhasil!</h2>
                    <p class="mt-3 text-emerald-700">
                        Terima kasih. Data Anda telah kami terima dan akan ditinjau oleh tim HR.
                    </p>
                    <a href="{{ route('register') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Daftar Lagi
                    </a>
                </div>
            </div>
        @else
            {{-- Stepper Indicator --}}
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    @foreach ($stepLabels as $num => $label)
                        <div class="flex flex-col items-center flex-1">
                            <button
                                type="button"
                                wire:click="goToStep({{ $num }})"
                                @class([
                                    'flex h-10 w-10 items-center justify-center rounded-full border-2 text-sm font-semibold transition',
                                    'border-amber-500 bg-amber-500 text-white' => $step === $num,
                                    'border-emerald-500 bg-emerald-500 text-white' => $step > $num,
                                    'border-gray-300 bg-white text-gray-500 hover:border-gray-400' => $step < $num,
                                ])
                            >
                                @if ($step > $num)
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                @else
                                    {{ $num }}
                                @endif
                            </button>
                            <span @class([
                                'mt-2 text-center text-xs font-medium',
                                'text-amber-600' => $step === $num,
                                'text-emerald-600' => $step > $num,
                                'text-gray-500' => $step < $num,
                            ])>
                                {{ $label }}
                            </span>
                        </div>

                        @if (! $loop->last)
                            <div @class([
                                'mb-6 h-0.5 flex-1 transition',
                                'bg-emerald-500' => $step > $num,
                                'bg-gray-300' => $step <= $num,
                            ])></div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Progress bar --}}
            <div class="mb-4 h-1.5 w-full overflow-hidden rounded-full bg-gray-200">
                <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500 transition-all duration-300"
                     style="width: {{ ($step / $totalSteps) * 100 }}%"></div>
            </div>

            {{-- Form Card --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
                {{-- Card Header --}}
                <div class="border-b border-gray-200 bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $stepLabels[$step] }}</h2>
                            <p class="mt-0.5 text-sm text-gray-600">Langkah {{ $step }} dari {{ $totalSteps }}</p>
                        </div>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-amber-700 shadow-sm">
                            {{ round(($step / $totalSteps) * 100) }}% selesai
                        </span>
                    </div>
                </div>

                {{-- Form Body --}}
                <form wire:submit.prevent="submit">
                    <div class="fi-form-wrapper px-6 py-6">
                        {!! $this->form->toHtml() !!}
                    </div>

                    {{-- Step Error Message --}}
                    @if ($errorMessage)
                        <div class="mx-6 mb-4 rounded-lg border border-rose-200 bg-rose-50 p-4">
                            <div class="flex gap-2">
                                <svg class="h-5 w-5 shrink-0 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-rose-800">Mohon lengkapi data berikut:</h3>
                                    <p class="mt-1 text-sm text-rose-700">{{ $errorMessage }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Navigation Footer --}}
                    <div class="flex flex-col gap-3 border-t border-gray-200 bg-gray-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            @if ($step > 1)
                                <button type="button"
                                        wire:click="previousStep"
                                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-100">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                    </svg>
                                    Sebelumnya
                                </button>
                            @else
                                <p class="text-xs text-gray-500">
                                    Semua bagian opsional boleh dilewati.
                                </p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            @if ($step < $totalSteps)
                                <button type="button"
                                        wire:click="nextStep"
                                        class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                    Selanjutnya
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </button>
                            @else
                                <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-8 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                    </svg>
                                    Kirim Pendaftaran
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer note --}}
            <p class="mt-6 text-center text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. Data kesehatan Anda dijaga kerahasiaannya sesuai UU No. 27/2022.
            </p>
        @endif
    </div>
</div>
