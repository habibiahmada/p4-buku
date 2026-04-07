<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aksara Pustaka') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $appName = config('app.name', 'Aksara Pustaka');
    $context = match (true) {
        request()->routeIs('login') => [
            'icon' => 'heroicon-o-arrow-right-end-on-rectangle',
            'badge' => 'Portal Masuk',
            'title' => 'Masuk untuk melanjutkan alur kerja perpustakaan.',
            'copy' => 'Buka dashboard, tinjau akun, dan lanjutkan pengelolaan layanan baca dari satu ruang kerja yang rapi.',
        ],
        request()->routeIs('register') => [
            'icon' => 'heroicon-o-user-plus',
            'badge' => 'Pendaftaran Anggota',
            'title' => 'Buat akun baru dengan tampilan yang serasi.',
            'copy' => 'Mulai pengalaman digital perpustakaan dengan akun yang siap digunakan untuk akses koleksi dan layanan.',
        ],
        request()->routeIs('password.request') => [
            'icon' => 'heroicon-o-envelope',
            'badge' => 'Pemulihan Akses',
            'title' => 'Pulihkan akses akun dengan proses yang sederhana.',
            'copy' => 'Kami kirimkan tautan reset kata sandi ke email Anda agar akses bisa kembali aktif dengan aman.',
        ],
        request()->routeIs('password.reset') => [
            'icon' => 'heroicon-o-key',
            'badge' => 'Kata Sandi Baru',
            'title' => 'Tetapkan kredensial baru untuk akun Anda.',
            'copy' => 'Gunakan kata sandi yang kuat agar ruang kerja perpustakaan tetap aman dan mudah dikelola.',
        ],
        request()->routeIs('verification.notice') => [
            'icon' => 'heroicon-o-shield-check',
            'badge' => 'Verifikasi Email',
            'title' => 'Aktifkan akun sebelum mulai menggunakan sistem.',
            'copy' => 'Konfirmasi email membantu menjaga akses pengguna tetap valid, aman, dan tertata.',
        ],
        request()->routeIs('password.confirm') => [
            'icon' => 'heroicon-o-lock-closed',
            'badge' => 'Konfirmasi Keamanan',
            'title' => 'Konfirmasi kata sandi sebelum melanjutkan.',
            'copy' => 'Langkah tambahan ini melindungi perubahan penting agar tetap dilakukan oleh pemilik akun yang sah.',
        ],
        default => [
            'icon' => 'heroicon-o-building-library',
            'badge' => 'Akses Akun',
            'title' => 'Masuk ke ruang baca digital Anda.',
            'copy' => 'Antarmuka ini menggunakan bahasa visual yang sama dengan landing page agar seluruh pengalaman aplikasi terasa utuh.',
        ],
    };

    $highlights = [
        [
            'icon' => 'heroicon-o-book-open',
            'title' => 'Koleksi Tertata',
            'copy' => 'Susun data buku, status stok, dan aktivitas peminjaman dalam satu tempat.',
        ],
        [
            'icon' => 'heroicon-o-users',
            'title' => 'Akun Terpusat',
            'copy' => 'Kelola akses pengguna, identitas anggota, dan pembaruan profil dengan alur yang konsisten.',
        ],
        [
            'icon' => 'heroicon-o-chart-bar',
            'title' => 'Siap Berkembang',
            'copy' => 'Dasar antarmuka sudah rapi untuk dikembangkan menjadi sistem perpustakaan sekolah yang lengkap.',
        ],
    ];
@endphp

<body>
    <div class="relative min-h-screen overflow-hidden">
        <div class="pointer-events-none absolute left-0 top-0 h-80 w-80 bg-sage/15 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-96 w-96 bg-copper/10 blur-3xl"></div>

        <main class="relative mx-auto flex min-h-screen max-w-6xl items-center px-6 py-10 sm:py-14">
            <div class="grid w-full gap-8 lg:grid-cols-[1.05fr_0.95fr] lg:gap-10">
                <section class="paper-panel hidden p-10 lg:flex lg:flex-col lg:justify-between">
                    <div class="space-y-8">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-4">
                            <x-application-logo class="h-14 w-14" />
                            <div>
                                <p class="font-serif text-2xl font-semibold text-ink">{{ $appName }}</p>
                                <p class="font-mono text-[11px] uppercase tracking-[0.28em] text-muted">Perpustakaan digital</p>
                            </div>
                        </a>

                        <div class="space-y-5">
                            <span class="section-kicker">
                                <x-dynamic-component :component="$context['icon']" class="h-4 w-4" />
                                {{ $context['badge'] }}
                            </span>
                            <h1 class="section-title max-w-[12ch]">{{ $context['title'] }}</h1>
                            <p class="section-copy max-w-xl">{{ $context['copy'] }}</p>
                        </div>
                    </div>

                    <div class="grid gap-4">
                        @foreach ($highlights as $highlight)
                            <div class="border border-ink/10 bg-white/70 p-5">
                                <div class="mb-4 flex h-11 w-11 items-center justify-center bg-sage-light text-sage">
                                    <x-dynamic-component :component="$highlight['icon']" class="h-5 w-5" />
                                </div>
                                <h2 class="font-serif text-xl font-semibold text-ink">{{ $highlight['title'] }}</h2>
                                <p class="mt-2 text-sm leading-7 text-muted">{{ $highlight['copy'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="surface-panel w-full p-6 sm:p-8 lg:p-10">
                    <div class="mb-8 flex items-center justify-between gap-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <x-application-logo class="h-12 w-12" />
                            <div>
                                <p class="font-serif text-xl font-semibold text-ink">{{ $appName }}</p>
                                <p class="font-mono text-[10px] uppercase tracking-[0.26em] text-muted">Ruang akses akun</p>
                            </div>
                        </a>

                        <span class="border border-sage/15 bg-sage-light/80 px-3 py-2 font-mono text-[10px] uppercase tracking-[0.24em] text-sage">
                            {{ $context['badge'] }}
                        </span>
                    </div>

                    {{ $slot }}

                    <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-ink/10 pt-6">
                        <a href="{{ route('home') }}" class="btn-secondary">
                            <x-heroicon-o-home class="h-4 w-4" />
                            Landing Page
                        </a>
                        <a href="{{ route('login') }}" class="topbar-link {{ request()->routeIs('login') ? 'topbar-link-active' : '' }}">
                            <x-heroicon-o-arrow-right-end-on-rectangle class="h-4 w-4" />
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="topbar-link {{ request()->routeIs('register') ? 'topbar-link-active' : '' }}">
                            <x-heroicon-o-user-plus class="h-4 w-4" />
                            Daftar
                        </a>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>

</html>
