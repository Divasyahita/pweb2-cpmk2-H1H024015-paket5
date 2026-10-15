<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Bengkelin — Sistem Servis Kendaraan' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-[#f8f7fc] text-gray-800">
    <div class="min-h-screen lg:flex">

        {{-- Sidebar --}}
        <aside class="w-full border-b border-purple-100 bg-white lg:fixed lg:inset-y-0 lg:left-0 lg:flex lg:w-64 lg:flex-col lg:border-b-0 lg:border-r">

            {{-- Logo --}}
            <div class="flex items-center gap-3 px-6 py-6">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-purple-800 text-white shadow-lg shadow-purple-200">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="24" height="24" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-lg font-extrabold tracking-tight text-gray-900">
                        Bengkel<span class="text-violet-600">in.</span>
                    </h1>
                    <p class="text-xs text-gray-400">Vehicle Service System</p>
                </div>
            </div>

            {{-- Menu --}}
            <div class="px-4 pt-3">
                <p class="mb-3 px-3 text-[10px] font-extrabold uppercase tracking-[0.18em] text-gray-400">
                    Menu Utama
                </p>

                <nav class="space-y-2">

                    {{-- Dashboard --}}
                    <a href="{{ url('/') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-purple-50 hover:text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="shrink-0 text-violet-600"
                             aria-hidden="true">
                            <rect x="3" y="3" width="8" height="8" rx="2"/>
                            <rect x="13" y="3" width="8" height="5" rx="2"/>
                            <rect x="13" y="10" width="8" height="11" rx="2"/>
                            <rect x="3" y="13" width="8" height="8" rx="2"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    {{-- Data Pelanggan --}}
                    <a href="{{ route('customers.index') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-purple-50 hover:text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="shrink-0 text-violet-600"
                             aria-hidden="true">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="10" cy="7" r="4"/>
                            <path d="M20 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Data Pelanggan</span>
                    </a>

                    {{-- Data Kendaraan --}}
                    <a href="{{ route('vehicles.index') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-purple-50 hover:text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="shrink-0 text-violet-600"
                             aria-hidden="true">
                            <path d="M5 17h14l1-6-2-5H6l-2 5z"/>
                            <path d="M4 11h16"/>
                            <path d="M7 17v2"/>
                            <path d="M17 17v2"/>
                            <circle cx="7.5" cy="14" r="1"/>
                            <circle cx="16.5" cy="14" r="1"/>
                        </svg>
                        <span>Data Kendaraan</span>
                    </a>

                    {{-- Data Servis --}}
                    <a href="{{ route('services.index') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-purple-50 hover:text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="20" height="20" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="shrink-0 text-violet-600"
                             aria-hidden="true">
                            <path d="M14.7 6.3a5 5 0 0 0-6.4 6.4L3 18l3 3 5.3-5.3a5 5 0 0 0 6.4-6.4L14 13l-3-3z"/>
                            <path d="m15 5 4-2 4 4-2 4"/>
                        </svg>
                        <span>Data Servis</span>
                    </a>

                </nav>
            </div>

            {{-- Informasi --}}
            <div class="mt-6 px-6 pb-6 lg:mt-auto">
                <div class="rounded-2xl bg-gradient-to-br from-violet-600 to-purple-800 p-4 text-white">
                    <p class="text-sm font-bold">Bengkelin Care</p>
                    <p class="mt-1 text-xs leading-relaxed text-purple-100">
                        Kelola data dan riwayat servis kendaraan dengan lebih mudah.
                    </p>
                </div>

                <p class="mt-5 text-center text-[10px] text-gray-400">
                    © {{ date('Y') }} Bengkelin
                </p>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="min-w-0 flex-1 lg:ml-64">
            <header class="sticky top-0 z-20 flex items-center justify-between border-b border-purple-100/70 bg-white/90 px-5 py-4 backdrop-blur-md sm:px-8">
                <div>
                    <p class="text-xs font-medium text-gray-400">Sistem Manajemen Bengkel</p>
                    <h2 class="mt-1 text-lg font-extrabold text-gray-900">
                        {{ $header ?? 'Bengkel Servis Kendaraan' }}
                    </h2>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 font-bold text-violet-700">
                    DS
                </div>
            </header>

            <div class="p-5 sm:p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>