@php
    $user = auth()->user();
    $firstName = explode(' ', trim($user->name))[0];
    $overview = [
        [
            'icon' => 'heroicon-o-book-open',
            'title' => 'Koleksi',
            'copy' => 'Rancang katalog buku, kategori, dan status stok dari satu struktur data yang lebih rapi.',
        ],
        [
            'icon' => 'heroicon-o-arrow-path',
            'title' => 'Sirkulasi',
            'copy' => 'Siapkan proses peminjaman dan pengembalian dengan tampilan yang kini konsisten di seluruh aplikasi.',
        ],
        [
            'icon' => 'heroicon-o-users',
            'title' => 'Pengguna',
            'copy' => 'Kelola identitas anggota, admin, dan preferensi akun melalui halaman profil yang diperbarui.',
        ],
        [
            'icon' => 'heroicon-o-chart-bar',
            'title' => 'Laporan',
            'copy' => 'Bangun dashboard statistik di atas fondasi UI yang sudah siap untuk dikembangkan lebih jauh.',
        ],
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <span class="section-kicker">
                    <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                    Dashboard
                </span>
                <div class="space-y-3">
                    <h1 class="section-title">Selamat datang kembali, {{ $firstName }}.</h1>
                    <p class="section-copy max-w-3xl">
                        Ruang kerja utama kini memakai bahasa visual yang sama dengan landing page {{ config('app.name', 'Aksara Pustaka') }}.
                    </p>
                </div>
            </div>

            <div class="paper-panel flex items-center gap-4 px-5 py-4">
                <div class="flex h-12 w-12 items-center justify-center bg-sage-light text-sage">
                    <x-heroicon-o-user-circle class="h-6 w-6" />
                </div>
                <div>
                    <p class="font-mono text-[11px] uppercase tracking-[0.24em] text-muted">Akun Aktif</p>
                    <p class="text-sm text-ink">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-6 py-10">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <section class="surface-panel p-7 sm:p-8">
                <div class="mb-8 space-y-3">
                    <span class="section-kicker">
                        <x-heroicon-o-building-library class="h-4 w-4" />
                        Fondasi Antarmuka
                    </span>
                    <h2 class="font-serif text-3xl font-bold text-ink">Semua halaman utama sekarang tampil selaras.</h2>
                    <p class="section-copy max-w-3xl">
                        Dashboard ini menegaskan bahwa aplikasi sudah memiliki fondasi visual yang konsisten untuk modul koleksi, transaksi, anggota, dan laporan.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach ($overview as $item)
                        <article class="border border-ink/10 bg-linen/70 p-5">
                            <div class="mb-4 flex h-11 w-11 items-center justify-center bg-white text-sage shadow-sm">
                                <x-dynamic-component :component="$item['icon']" class="h-5 w-5" />
                            </div>
                            <h3 class="font-serif text-xl font-semibold text-ink">{{ $item['title'] }}</h3>
                            <p class="mt-2 text-sm leading-7 text-muted">{{ $item['copy'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <aside class="space-y-6">
                <section class="surface-panel p-7">
                    <span class="section-kicker">
                        <x-heroicon-o-bolt class="h-4 w-4" />
                        Aksi Cepat
                    </span>
                    <div class="mt-5 space-y-3">
                        <a href="{{ route('profile.edit') }}" class="flex items-center justify-between border border-ink/10 bg-linen/70 px-5 py-4 transition hover:border-sage/30 hover:bg-white">
                            <span>
                                <span class="block font-serif text-xl font-semibold text-ink">Edit profil</span>
                                <span class="mt-1 block text-sm text-muted">Perbarui data akun dan kata sandi Anda.</span>
                            </span>
                            <x-heroicon-o-arrow-up-right class="h-5 w-5 text-sage" />
                        </a>
                        <a href="{{ route('home') }}" class="flex items-center justify-between border border-ink/10 bg-linen/70 px-5 py-4 transition hover:border-sage/30 hover:bg-white">
                            <span>
                                <span class="block font-serif text-xl font-semibold text-ink">Lihat landing page</span>
                                <span class="mt-1 block text-sm text-muted">Tinjau referensi desain utama aplikasi.</span>
                            </span>
                            <x-heroicon-o-arrow-up-right class="h-5 w-5 text-sage" />
                        </a>
                    </div>
                </section>

                <section class="paper-panel p-7">
                    <span class="section-kicker">
                        <x-heroicon-o-shield-check class="h-4 w-4" />
                        Status Ruang Kerja
                    </span>
                    <ul class="mt-5 space-y-4">
                        <li class="flex items-start gap-3 text-sm text-muted">
                            <x-heroicon-o-check-circle class="mt-0.5 h-5 w-5 flex-shrink-0 text-sage" />
                            Branding aplikasi sudah berganti menjadi {{ config('app.name', 'Aksara Pustaka') }}.
                        </li>
                        <li class="flex items-start gap-3 text-sm text-muted">
                            <x-heroicon-o-check-circle class="mt-0.5 h-5 w-5 flex-shrink-0 text-sage" />
                            Komponen tombol, input, dropdown, dan modal sudah memakai gaya visual baru.
                        </li>
                        <li class="flex items-start gap-3 text-sm text-muted">
                            <x-heroicon-o-check-circle class="mt-0.5 h-5 w-5 flex-shrink-0 text-sage" />
                            Halaman auth, dashboard, profile, dan landing page kini memakai bahasa desain yang sama.
                        </li>
                    </ul>
                </section>
            </aside>
        </div>
    </div>
</x-app-layout>
