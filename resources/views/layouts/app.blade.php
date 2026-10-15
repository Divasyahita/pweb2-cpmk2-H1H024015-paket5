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
            <div class="flex items-center gap-3 px-6 py-6">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500 to-purple-800 text-xl text-white shadow-lg shadow-purple-200">
                    🔧
                </div>

                <div>
                    <h1 class="text-lg font-extrabold tracking-tight text-gray-900">
                        Bengkel<span class="text-violet-600">in.</span>
                    </h1>
                    <p class="text-xs text-gray-400">Vehicle Service System</p>
                </div>
            </div>

            <div class="px-4 pt-3">
                <p class="mb-3 px-3 text-[10px] font-extrabold uppercase tracking-[0.18em] text-gray-400">
                    Menu Utama
                </p>

                <nav class="space-y-2">
                    <a href="{{ url('/') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-purple-50 hover:text-violet-700">
                        <span>🏠</span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('customers.index') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-purple-50 hover:text-violet-700">
                        <span>👥</span>
                        <span>Data Pelanggan</span>
                    </a>

                    <a href="{{ route('vehicles.index') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-purple-50 hover:text-violet-700">
                        <span>🚘</span>
                        <span>Data Kendaraan</span>
                    </a>

                    <a href="{{ route('services.index') }}"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition hover:bg-purple-50 hover:text-violet-700">
                        <span>🛠️</span>
                        <span>Data Servis</span>
                    </a>
                </nav>
            </div>

            <div class="mt-6 px-6 pb-6 lg:mt-auto">
                <div class="rounded-2xl bg-gradient-to-br from-violet-600 to-purple-800 p-4 text-white">
                    <p class="text-sm font-bold">Bengkelin Care ✨</p>
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