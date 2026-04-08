<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Aksara Pustaka') }} - Perpustakaan Digital</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #F7F2EA;
        }

        .fade-in {
            animation: fadeIn 0.6s ease both;
        }

        .fade-up {
            animation: fadeUp 0.7s ease both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .d1 {
            animation-delay: .1s;
        }

        .d2 {
            animation-delay: .22s;
        }

        .d3 {
            animation-delay: .36s;
        }

        .d4 {
            animation-delay: .50s;
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

@php
    $appName = config('app.name', 'Aksara Pustaka');
    $initials = collect(explode(' ', $appName))
        ->filter()
        ->take(2)
        ->map(fn($segment) => strtoupper(substr($segment, 0, 1)))
        ->implode('');

    $features = [
        [
            'heroicon-o-book-open',
            'F-01',
            'Manajemen Koleksi',
            'Tambah, edit, hapus, dan cari buku. Pantau stok secara real-time dengan pembaruan otomatis di setiap transaksi.',
        ],
        [
            'heroicon-o-user-circle',
            'F-02',
            'Manajemen Anggota',
            'Kelola data siswa dan admin. Role-based access memastikan setiap pengguna hanya mengakses fitur yang sesuai.',
        ],
        [
            'heroicon-o-clipboard-document-list',
            'F-03',
            'Proses Peminjaman',
            'Pinjam multi-buku dalam satu sesi. Pilih judul, tentukan tanggal, sistem mencatat semuanya secara otomatis.',
        ],
        [
            'heroicon-o-arrow-path',
            'F-04',
            'Proses Pengembalian',
            'Verifikasi via nomor transaksi. Status dan stok buku diperbarui otomatis setelah pengembalian dikonfirmasi.',
        ],
        [
            'heroicon-o-banknotes',
            'F-05',
            'Kalkulasi Denda',
            'Denda keterlambatan dihitung otomatis berdasarkan tanggal jatuh tempo. Transparan dan akurat setiap saat.',
        ],
        [
            'heroicon-o-chart-bar',
            'F-06',
            'Riwayat Transaksi',
            'Arsip lengkap seluruh peminjaman dan pengembalian. Filter berdasarkan tanggal, status, atau nama anggota.',
        ],
    ];

    $stats = [
        ['10K+', 'Judul Buku'],
        ['2K+', 'Anggota Aktif'],
        ['500+', 'Transaksi / Bulan'],
        ['<3s', 'Response Time'],
    ];

    $stack = [
        [
            'heroicon-o-cog-6-tooth',
            'Backend',
            'PHP + Laravel 13',
            'Framework modern dengan ekosistem lengkap untuk aplikasi web yang handal.',
        ],
        [
            'heroicon-o-circle-stack',
            'Database',
            'MySQL',
            'Relational database dengan skema USERS, BOOKS, BORROWINGS, BORROWING_DETAILS.',
        ],
        [
            'heroicon-o-swatch',
            'Frontend',
            'Blade + Tailwind',
            'Template engine Laravel dengan utility-first CSS untuk UI yang responsif.',
        ],
        [
            'heroicon-o-lock-closed',
            'Keamanan',
            'RBAC Authentication',
            'Role-based access control yang memisahkan hak akses admin dan anggota.',
        ],
    ];

    $checks = [
        'Peminjaman &amp; pengembalian otomatis',
        'Kalkulasi denda keterlambatan',
        'Manajemen stok buku real-time',
        'Dashboard terpisah per role',
        'Riwayat &amp; laporan transaksi',
        'Response time &lt; 3 dtk',
        'Didukung Chrome, Firefox, Safari',
    ];
@endphp

<body class="font-sans text-ink antialiased">

    <nav class="sticky top-0 z-50 border-b border-ink/10 bg-linen/90 backdrop-blur-sm">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center bg-sage">
                    <span class="text-[11px] font-mono font-medium text-linen">{{ $initials }}</span>
                </div>
                <span class="font-serif text-lg font-semibold tracking-tight">{{ $appName }}</span>
                <span
                    class="hidden border border-muted/40 px-1.5 py-0.5 font-mono text-[10px] text-muted sm:inline">v1.0</span>
            </div>

            <div class="hidden items-center gap-8 md:flex">
                <a href="#fitur"
                    class="font-mono text-[11px] uppercase tracking-widest text-muted transition-colors hover:text-ink">Fitur</a>
                <a href="#akses"
                    class="font-mono text-[11px] uppercase tracking-widest text-muted transition-colors hover:text-ink">Akses</a>
                <a href="#teknologi"
                    class="font-mono text-[11px] uppercase tracking-widest text-muted transition-colors hover:text-ink">Stack</a>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="hidden font-mono text-[11px] uppercase tracking-widest text-muted transition-colors hover:text-ink sm:inline">Dashboard</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}"
                        class="hidden font-mono text-[11px] uppercase tracking-widest text-muted transition-colors hover:text-ink sm:inline">Masuk</a>
                    <a href="{{ route('register') }}"
                        class="bg-sage px-4 py-2.5 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors hover:bg-sage/85">Daftar
                        &rarr;</a>
                @endguest
            </div>
        </div>
    </nav>

    <section class="mx-auto max-w-6xl px-6 py-36">
        <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-12">
            <div class="lg:col-span-7">
                <div
                    class="fade-in mb-8 inline-flex items-center gap-2 bg-sage-light px-3 py-1.5 font-mono text-[11px] uppercase tracking-widest text-sage">
                    <span class="h-1.5 w-1.5 bg-sage animate-pulse"></span>
                    Sistem Perpustakaan Digital
                </div>

                <h1
                    class="fade-up d1 mb-6 font-serif text-5xl font-bold leading-[1.05] tracking-tight sm:text-6xl lg:text-[4.25rem]">
                    Kelola<br>
                    <em class="font-normal italic not-italicy' text-sage">Perpustakaan</em><br>
                    Anda Secara Digital.
                </h1>

                <p class="fade-up d2 mb-10 max-w-[44ch] font-sans text-[1.05rem] font-light leading-relaxed text-muted">
                    {{ $appName }} mengotomasi seluruh alur kerja perpustakaan sekolah: peminjaman, pengembalian,
                    stok buku, denda, hingga laporan &mdash; dalam satu platform terpadu.
                </p>

                <div class="fade-up d3 flex flex-wrap gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="bg-sage px-7 py-3.5 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors hover:bg-sage/85">
                            Buka Dashboard
                        </a>

                    @endauth
                    @guest
                        <a href="{{ route('register') }}"
                            class="bg-ink px-7 py-3.5 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors duration-200 hover:bg-sage">
                            Mulai Gratis
                        </a>
                    @endguest
                    <a href="#fitur"
                        class="border border-ink/30 px-7 py-3.5 font-mono text-[11px] uppercase tracking-widest text-ink transition-colors duration-200 hover:border-sage hover:text-sage">
                        Lihat Fitur &darr;
                    </a>
                </div>
            </div>

            <div class="relative flex items-center justify-center lg:col-span-5 lg:justify-end"
                style="min-height: 360px;">
                <div class="absolute w-64 border border-copper/20 bg-copper-light"
                    style="height:320px; top:18px; right:0; transform:rotate(4deg);"></div>
                <div class="absolute w-64 border border-ink/10 bg-parch"
                    style="height:320px; top:9px; right:0; transform:rotate(1.8deg);"></div>

                <div class="fade-up d2 absolute w-64 overflow-hidden border border-ink/10 bg-white"
                    style="height:320px; top:0; right:0; box-shadow:0 20px 48px -12px rgba(28,25,23,0.10);">

                    <div class="h-1 w-full bg-sage"></div>

                    <div class="px-6 pb-4 pt-5">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="flex h-8 w-8 items-center justify-center bg-sage">
                                    <span
                                        class="font-mono text-[10px] font-medium text-linen">{{ $initials }}</span>
                                </div>
                                <span
                                    class="font-serif text-sm font-semibold tracking-tight text-ink">{{ $appName }}</span>
                            </div>
                            <div class="flex items-center gap-1.5 bg-sage-light px-2 py-1">
                                <x-heroicon-o-check-circle class="h-3.5 w-3.5 text-sage" />
                                <span class="font-mono text-[9px] uppercase tracking-widest text-sage">Aktif</span>
                            </div>
                        </div>

                        <p class="mb-1 font-mono text-[9px] uppercase tracking-widest text-muted">Perpustakaan Digital
                        </p>
                        <p class="font-serif text-[1.05rem] font-bold leading-snug text-ink">
                            Sistem Manajemen<br>
                            <em class="font-normal text-sage">Terpadu</em>
                        </p>
                    </div>

                    <div class="mx-6 border-t border-ink/8"></div>

                    <div class="space-y-0 px-6 py-3">
                        <div class="flex items-center justify-between border-b border-ink/[0.06] py-2.5">
                            <span class="font-mono text-[9px] uppercase tracking-widest text-muted">Koleksi</span>
                            <div class="text-right">
                                <span class="font-serif text-sm font-bold text-ink">10.000+</span>
                                <span class="ml-1 font-mono text-[9px] text-muted">buku</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between border-b border-ink/[0.06] py-2.5">
                            <span class="font-mono text-[9px] uppercase tracking-widest text-muted">Anggota</span>
                            <div class="text-right">
                                <span class="font-serif text-sm font-bold text-ink">2.000+</span>
                                <span class="ml-1 font-mono text-[9px] text-muted">siswa</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="font-mono text-[9px] uppercase tracking-widest text-muted">Response</span>
                            <span class="font-serif text-sm font-bold text-sage">&lt; 3 dtk</span>
                        </div>
                    </div>

                    <div class="absolute bottom-0 left-0 right-0 flex items-center justify-between bg-ink px-6 py-2.5">
                        <span class="font-mono text-[9px] uppercase tracking-wider text-linen/40">v1.0 &middot;
                            2025</span>
                        <span class="font-mono text-[9px] uppercase tracking-wider text-sage">Mulai Gratis &rarr;</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="bg-ink px-6 py-5">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center gap-x-10 gap-y-2">
            <span class="font-mono text-[11px] uppercase tracking-widest text-linen/30">Dibangun dengan</span>
            <span class="font-mono text-[11px] uppercase tracking-widest text-linen/60">Laravel 13</span>
            <span class="text-linen/20">&middot;</span>
            <span class="font-mono text-[11px] uppercase tracking-widest text-linen/60">MySQL</span>
            <span class="text-linen/20">&middot;</span>
            <span class="font-mono text-[11px] uppercase tracking-widest text-linen/60">Tailwind CSS</span>
            <span class="text-linen/20">&middot;</span>
            <span class="font-mono text-[11px] uppercase tracking-widest text-linen/60">Blade Template</span>
            <span class="hidden text-linen/20 lg:inline">&middot;</span>
            <span class="font-mono text-[11px] uppercase tracking-widest text-copper">Role-Based Access</span>
        </div>
    </div>

    <section id="fitur" class="mx-auto max-w-6xl px-6 py-28">
        <div class="reveal mb-16 flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
            <div>
                <span class="mb-3 block font-mono text-[11px] uppercase tracking-widest text-copper">01 / Fitur
                    Sistem</span>
                <h2 class="font-serif text-4xl font-bold leading-tight sm:text-5xl">
                    Semua yang dibutuhkan<br>
                    <em class="font-normal italic not-italic">perpustakaan modern.</em>
                </h2>
            </div>
            <p class="hidden max-w-[34ch] font-sans text-sm font-light leading-relaxed text-muted sm:block">
                Dirancang untuk menggantikan pencatatan manual dengan alur kerja yang otomatis dan akurat.
            </p>
        </div>

        <div class="reveal grid grid-cols-1 gap-px bg-ink/10 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features as [$icon, $num, $title, $desc])
                <div class="group bg-linen p-8 transition-colors duration-300 hover:bg-sage">
                    <div
                        class="mb-6 flex h-10 w-10 items-center justify-center bg-sage-light transition-colors group-hover:bg-white/20">
                        <x-dynamic-component :component="$icon"
                            class="h-5 w-5 text-sage transition-colors group-hover:text-white" />
                    </div>
                    <p
                        class="mb-2 font-mono text-[10px] uppercase tracking-widest text-muted transition-colors group-hover:text-white/40">
                        {{ $num }}</p>
                    <h3
                        class="mb-3 font-serif text-lg font-semibold text-ink transition-colors group-hover:text-white">
                        {{ $title }}</h3>
                    <p
                        class="font-sans text-sm font-light leading-relaxed text-muted transition-colors group-hover:text-white/65">
                        {{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="akses" class="bg-parch">
        <div class="mx-auto max-w-6xl px-6 py-28">
            <div class="reveal mb-16">
                <span class="mb-3 block font-mono text-[11px] uppercase tracking-widest text-copper">02 / Akses
                    Pengguna</span>
                <h2 class="font-serif text-4xl font-bold leading-tight sm:text-5xl">
                    Dua peran, <em class="font-normal italic not-italic">satu sistem.</em>
                </h2>
            </div>

            <div class="reveal grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="relative overflow-hidden bg-ink p-10">
                    <span
                        class="pointer-events-none absolute -right-3 -top-6 select-none font-serif text-[9rem] font-bold leading-none text-white/[0.04]">01</span>
                    <span
                        class="mb-8 inline-block border border-sage/40 px-3 py-1 font-mono text-[10px] uppercase tracking-widest text-sage">Admin</span>
                    <h3 class="mb-3 font-serif text-3xl font-bold text-linen">
                        Kendali <em class="font-normal italic not-italic text-sage">Penuh</em>
                    </h3>
                    <p class="mb-8 max-w-[38ch] font-sans text-sm font-light leading-relaxed text-linen/50">
                        Dashboard terpusat untuk mengelola seluruh aspek perpustakaan &mdash; dari koleksi hingga
                        laporan transaksi.
                    </p>
                    <ul class="mb-10 space-y-3">
                        @foreach (['Dashboard statistik & laporan', 'CRUD buku & manajemen stok', 'CRUD data anggota & role', 'Proses & verifikasi peminjaman', 'Konfirmasi pengembalian buku', 'Riwayat transaksi lengkap'] as $item)
                            <li class="flex items-center gap-3 text-sm text-linen/65">
                                <x-heroicon-o-check class="h-3.5 w-3.5 flex-shrink-0 text-sage" />
                                <span class="font-sans font-light">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="inline-block bg-sage px-6 py-3 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors hover:bg-sage/80">
                            Buka Dashboard &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-block bg-sage px-6 py-3 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors hover:bg-sage/80">
                            Masuk sistem &rarr;
                        </a>
                    @endauth
                </div>

                <div class="relative overflow-hidden bg-sage-light p-10">
                    <span
                        class="pointer-events-none absolute -right-3 -top-6 select-none font-serif text-[9rem] font-bold leading-none text-sage/[0.1]">02</span>
                    <span
                        class="mb-8 inline-block border border-sage/40 px-3 py-1 font-mono text-[10px] uppercase tracking-widest text-sage">Anggota
                        / Siswa</span>
                    <h3 class="mb-3 font-serif text-3xl font-bold text-ink">
                        Akses <em class="font-normal italic not-italic text-sage">Mandiri</em>
                    </h3>
                    <p class="mb-8 max-w-[38ch] font-sans text-sm font-light leading-relaxed text-muted">
                        Nikmati layanan perpustakaan kapan saja. Ajukan peminjaman, pantau status, dan lihat riwayat
                        pribadi Anda.
                    </p>
                    <ul class="mb-10 space-y-3">
                        @foreach (['Registrasi & login mandiri', 'Telusuri & ajukan peminjaman', 'Pantau status transaksi aktif', 'Lihat denda keterlambatan', 'Riwayat peminjaman pribadi', 'Kelola profil akun'] as $item)
                            <li class="flex items-center gap-3 text-sm text-muted">
                                <x-heroicon-o-check class="h-3.5 w-3.5 flex-shrink-0 text-sage" />
                                <span class="font-sans font-light">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('register') }}"
                        class="inline-block bg-ink px-6 py-3 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors duration-200 hover:bg-sage">
                        Daftar sebagai Anggota &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="reveal border-y border-ink/10">
        <div class="mx-auto grid max-w-6xl grid-cols-2 divide-x divide-ink/10 px-6 lg:grid-cols-4">
            @foreach ($stats as [$num, $label])
                <div class="px-6 py-10 text-center">
                    <p class="mb-1 font-serif text-4xl font-bold text-ink">{{ $num }}</p>
                    <p class="font-mono text-[10px] uppercase tracking-widest text-muted">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <section id="teknologi" class="mx-auto max-w-6xl px-6 py-28">
        <div class="reveal mb-16">
            <span class="mb-3 block font-mono text-[11px] uppercase tracking-widest text-copper">03 / Stack
                Teknologi</span>
            <h2 class="font-serif text-4xl font-bold leading-tight sm:text-5xl">
                Dibangun di atas pondasi <em class="font-normal italic not-italic">yang solid.</em>
            </h2>
        </div>

        <div class="reveal grid grid-cols-1 gap-px bg-ink/10 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stack as [$icon, $cat, $name, $desc])
                <div class="group bg-linen p-8 transition-colors duration-300 hover:bg-copper-light">
                    <div class="mb-5 flex h-10 w-10 items-center justify-center bg-white text-copper">
                        <x-dynamic-component :component="$icon" class="h-5 w-5" />
                    </div>
                    <p class="mb-1 font-mono text-[10px] uppercase tracking-widest text-copper">{{ $cat }}
                    </p>
                    <p
                        class="mb-3 font-serif text-lg font-semibold text-ink transition-colors group-hover:text-copper">
                        {{ $name }}</p>
                    <p class="font-sans text-sm font-light leading-relaxed text-muted">{{ $desc }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-wrap items-center gap-3">
            <span class="font-mono text-[11px] uppercase tracking-widest text-muted">Didukung di:</span>
            @foreach (['Chrome', 'Firefox', 'Safari'] as $browser)
                <span
                    class="cursor-default border border-ink/20 px-3 py-1.5 font-mono text-[11px] text-muted transition-colors hover:border-sage hover:text-sage">{{ $browser }}</span>
            @endforeach
        </div>
    </section>

    <section class="bg-ink">
        <div class="mx-auto max-w-6xl px-6 py-28">
            <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <span class="mb-6 block font-mono text-[11px] uppercase tracking-widest text-sage">Mulai
                        Sekarang</span>
                    <h2 class="mb-6 font-serif text-5xl font-bold leading-[1.05] text-linen sm:text-6xl">
                        Modernisasi<br>
                        <em class="font-normal italic not-italic text-sage">perpustakaan</em><br>
                        Anda hari ini.
                    </h2>
                    <p class="mb-10 max-w-[44ch] font-sans text-lg font-light leading-relaxed text-linen/50">
                        Tinggalkan pencatatan manual. {{ $appName }} hadir untuk mengotomasi seluruh alur kerja
                        perpustakaan sekolah dari peminjaman hingga laporan akhir bulan.
                    </p>
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="bg-sage px-8 py-4 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors hover:bg-sage/80">
                            Buka Dashboard
                        </a>
                    @endauth
                    @guest
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('register') }}"
                                class="bg-sage px-8 py-4 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors hover:bg-sage/80">
                                Buat Akun Gratis
                            </a>
                            <a href="{{ route('login') }}"
                                class="border border-linen/20 px-8 py-4 font-mono text-[11px] uppercase tracking-widest text-linen transition-colors hover:border-sage hover:text-sage">
                                Sudah Punya Akun &rarr;
                            </a>
                        @endguest
                    </div>
                </div>

                <div class="space-y-px lg:col-span-5">
                    @foreach ($checks as $check)
                        <div class="group flex cursor-default items-center gap-4 border-b border-white/5 py-4">
                            <span
                                class="flex h-5 w-5 flex-shrink-0 items-center justify-center bg-sage/15 transition-colors group-hover:bg-sage">
                                <x-heroicon-o-check
                                    class="h-3.5 w-3.5 text-sage transition-colors group-hover:text-linen" />
                            </span>
                            <span
                                class="font-sans text-sm text-linen/55 transition-colors group-hover:text-linen/85">{!! $check !!}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <footer class="border-t border-white/5 bg-ink">
        <div class="mx-auto max-w-6xl px-6 py-12">
            <div class="grid grid-cols-1 gap-10 sm:grid-cols-3">
                <div>
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center bg-sage">
                            <span class="text-[11px] font-mono font-medium text-linen">{{ $initials }}</span>
                        </div>
                        <span class="font-serif text-lg font-semibold text-linen">{{ $appName }}</span>
                    </div>
                    <p class="max-w-[28ch] font-sans text-sm font-light leading-relaxed text-linen/40">
                        Sistem manajemen perpustakaan digital untuk sekolah modern Indonesia.
                    </p>
                </div>

                <div>
                    <p class="mb-5 font-mono text-[10px] uppercase tracking-widest text-muted">Navigasi</p>
                    <ul class="space-y-3">
                        @foreach ([
        '#fitur' => 'Fitur Sistem',
        '#akses' => 'Akses Pengguna',
        '#teknologi' => 'Stack Teknologi',
    ] as $href => $label)
                            <li>
                                <a href="{{ $href }}"
                                    class="font-sans text-sm text-linen/45 transition-colors hover:text-sage">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <p class="mb-5 font-mono text-[10px] uppercase tracking-widest text-muted">Akun</p>
                    <ul class="space-y-3">
                        @guest
                            <li><a href="{{ route('login') }}"
                                    class="font-sans text-sm text-linen/45 transition-colors hover:text-sage">Login</a>
                            </li>
                            <li><a href="{{ route('register') }}"
                                    class="font-sans text-sm text-linen/45 transition-colors hover:text-sage">Daftar
                                    Anggota</a></li>
                        @endguest
                        @auth
                            <li><a href="{{ route('dashboard') }}"
                                    class="font-sans text-sm text-linen/45 transition-colors hover:text-sage">Dashboard</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>

            <div class="mt-10 flex flex-col justify-between gap-3 border-t border-white/5 pt-8 sm:flex-row">
                <p class="font-mono text-[11px] tracking-wider text-linen/20">&copy; {{ date('Y') }}
                    {{ $appName }}. All rights reserved.</p>
                <div class="flex items-center gap-2">
                    <x-heroicon-o-check-circle class="h-4 w-4 text-sage" />
                    <span class="font-mono text-[11px] tracking-wider text-sage/60">Sistem Aktif</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) e.target.classList.add('show');
            });
        }, {
            threshold: 0.08
        });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const t = document.querySelector(a.getAttribute('href'));
                if (t) {
                    e.preventDefault();
                    t.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>

</body>

</html>
