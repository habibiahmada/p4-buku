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
            'copy' =>
                'Buka dashboard, tinjau akun, dan lanjutkan pengelolaan layanan baca dari satu ruang kerja yang rapi.',
        ],
        request()->routeIs('register') => [
            'icon' => 'heroicon-o-user-plus',
            'badge' => 'Pendaftaran Anggota',
            'title' => 'Buat akun baru dengan tampilan yang serasi.',
            'copy' =>
                'Mulai pengalaman digital perpustakaan dengan akun yang siap digunakan untuk akses koleksi dan layanan.',
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
            'copy' =>
                'Langkah tambahan ini melindungi perubahan penting agar tetap dilakukan oleh pemilik akun yang sah.',
        ],
        default => [
            'icon' => 'heroicon-o-building-library',
            'badge' => 'Akses Akun',
            'title' => 'Masuk ke ruang baca digital Anda.',
            'copy' =>
                'Antarmuka ini menggunakan bahasa visual yang sama dengan landing page agar seluruh pengalaman aplikasi terasa utuh.',
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

        <main class="relative mx-auto flex min-h-screen max-w-6xl items-center justify-center px-6 py-10 sm:py-14">
            <div class="mx-auto max-w-2xl rounded-lg bg-white/80 shadow-lg backdrop-blur">
                <section class="surface-panel p-6 sm:p-8 lg:p-10">
                    <div class="mb-8 flex items-center justify-between gap-4">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <x-application-logo class="h-12 w-12" />
                            <div>
                                <p class="font-serif text-xl font-semibold text-ink">{{ $appName }}</p>
                                <p class="font-mono text-[10px] uppercase tracking-[0.26em] text-muted">Ruang akses akun
                                </p>
                            </div>
                        </a>

                        <span
                            class="border border-sage/15 bg-sage-light/80 px-3 py-2 font-mono text-[10px] uppercase tracking-[0.24em] text-sage">
                            {{ $context['badge'] }}
                        </span>
                    </div>

                    {{ $slot }}
                </section>
            </div>
        </main>
    </div>
</body>

</html>
