<?php

use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Service;
use Livewire\Component;

new class extends Component {
    public function getStatsProperty(): array
    {
        return [
            'customers' => Customer::count(),
            'vehicles' => Vehicle::count(),
            'services' => Service::count(),
        ];
    }
};
?>

<div class="mx-auto w-full max-w-7xl space-y-8">

    {{-- Welcome Banner --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-700 via-purple-600 to-violet-500 p-7 text-white shadow-lg shadow-purple-200/60 sm:p-9">
        <div class="relative z-10 max-w-2xl">
            <p class="text-sm font-semibold tracking-wide text-purple-200">
                DASHBOARD
            </p>

            <h1 class="mt-3 text-2xl font-extrabold tracking-tight sm:text-3xl">
                Selamat Datang di Bengkelin
            </h1>

            <p class="mt-3 max-w-xl text-sm leading-6 text-purple-100 sm:text-base">
                Kelola pelanggan, kendaraan, dan data servis dalam satu sistem
                yang praktis dan terorganisasi.
            </p>

            <a href="{{ route('services.index') }}"
               class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-violet-700 shadow-sm transition hover:bg-purple-50">
                Lihat Data Servis

                <svg xmlns="http://www.w3.org/2000/svg"
                     width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="pointer-events-none absolute -right-12 -top-20 h-64 w-64 rounded-full border-[35px] border-white/10"></div>
        <div class="pointer-events-none absolute -bottom-28 right-36 h-56 w-56 rounded-full bg-white/5"></div>
    </section>

    {{-- Section Heading --}}
    <section>
        <div class="mb-5">
            <h2 class="text-xl font-extrabold tracking-tight text-gray-900">
                Ringkasan Data
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Gambaran umum data yang tersimpan dalam sistem bengkel.
            </p>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Customers --}}
            <a href="{{ route('customers.index') }}"
               class="group min-w-0 rounded-2xl border border-purple-100 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-violet-300 hover:shadow-lg hover:shadow-purple-100">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="19" height="19" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         class="shrink-0 text-gray-300 transition group-hover:text-violet-600"
                         aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </div>

                <p class="mt-5 text-sm font-medium text-gray-500">
                    Total Pelanggan
                </p>
                <p class="mt-1 text-3xl font-extrabold tracking-tight text-gray-900">
                    {{ $this->stats['customers'] }}
                </p>
                <p class="mt-2 text-xs font-semibold text-violet-600">
                    Lihat data pelanggan
                </p>
            </a>

            {{-- Vehicles --}}
            <a href="{{ route('vehicles.index') }}"
               class="group min-w-0 rounded-2xl border border-purple-100 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-violet-300 hover:shadow-lg hover:shadow-purple-100">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M5 17h14l1-6-2-5H6l-2 5z"/>
                            <path d="M4 11h16"/>
                            <path d="M7 17v2"/>
                            <path d="M17 17v2"/>
                            <circle cx="7.5" cy="14" r="1"/>
                            <circle cx="16.5" cy="14" r="1"/>
                        </svg>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="19" height="19" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         class="shrink-0 text-gray-300 transition group-hover:text-violet-600"
                         aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </div>

                <p class="mt-5 text-sm font-medium text-gray-500">
                    Total Kendaraan
                </p>
                <p class="mt-1 text-3xl font-extrabold tracking-tight text-gray-900">
                    {{ $this->stats['vehicles'] }}
                </p>
                <p class="mt-2 text-xs font-semibold text-violet-600">
                    Lihat data kendaraan
                </p>
            </a>

            {{-- Services --}}
            <a href="{{ route('services.index') }}"
               class="group min-w-0 rounded-2xl border border-purple-100 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-violet-300 hover:shadow-lg hover:shadow-purple-100">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-violet-100 text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             aria-hidden="true">
                            <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3z"/>
                            <path d="m15 5 4-2 4 4-2 4"/>
                        </svg>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="19" height="19" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         class="shrink-0 text-gray-300 transition group-hover:text-violet-600"
                         aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </div>

                <p class="mt-5 text-sm font-medium text-gray-500">
                    Total Servis
                </p>
                <p class="mt-1 text-3xl font-extrabold tracking-tight text-gray-900">
                    {{ $this->stats['services'] }}
                </p>
                <p class="mt-2 text-xs font-semibold text-violet-600">
                    Lihat data servis
                </p>
            </a>

        </div>
    </section>

    {{-- Quick Access --}}
    <section class="rounded-2xl border border-purple-100 bg-white p-6 shadow-sm sm:p-7">
        <div>
            <h2 class="text-lg font-extrabold text-gray-900">
                Akses Cepat
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Pilih menu untuk mengelola data bengkel.
            </p>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <a href="{{ route('customers.index') }}"
               class="flex items-center justify-between gap-3 rounded-xl border border-purple-100 px-4 py-4 text-sm font-semibold text-gray-700 transition hover:border-violet-200 hover:bg-purple-50 hover:text-violet-700">
                <span>Data Pelanggan</span>
                <span class="text-lg text-violet-600">→</span>
            </a>

            <a href="{{ route('vehicles.index') }}"
               class="flex items-center justify-between gap-3 rounded-xl border border-purple-100 px-4 py-4 text-sm font-semibold text-gray-700 transition hover:border-violet-200 hover:bg-purple-50 hover:text-violet-700">
                <span>Data Kendaraan</span>
                <span class="text-lg text-violet-600">→</span>
            </a>

            <a href="{{ route('services.index') }}"
               class="flex items-center justify-between gap-3 rounded-xl border border-purple-100 px-4 py-4 text-sm font-semibold text-gray-700 transition hover:border-violet-200 hover:bg-purple-50 hover:text-violet-700">
                <span>Data Servis</span>
                <span class="text-lg text-violet-600">→</span>
            </a>
        </div>
    </section>

</div>