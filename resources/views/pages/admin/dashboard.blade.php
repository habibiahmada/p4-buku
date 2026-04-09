<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between ">
            <div class="space-y-4">
                <h2 class="section-kicker">
                    <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                    Admin Dashboard
                </h2>
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-900">
                        Ringkasan pengelolaan perpustakaan
                    </h1>
                    <p class="max-w-3xl text-sm text-gray-600 sm:text-base">
                        Pantau anggota, koleksi buku, dan aktivitas peminjaman dari satu halaman sederhana.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center justify-center  bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    Kelola Anggota
                </a>
                <a href="{{ route('admin.books.index') }}"
                    class="inline-flex items-center justify-center  border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Kelola Buku
                </a>
                <a href="{{ route('admin.transactions.index') }}"
                    class="inline-flex items-center justify-center  border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Lihat Peminjaman
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $chartMax = max($monthlyBorrowings->max('total'), 1);
        $totalTransaksi = $peminjamanAktif + $peminjamanSelesai;
        $totalPengguna = $totalAnggota + $totalAdmin;
        $totalKoleksiAktif = $totalStokBuku + $bukuDipinjam;
        $persentaseDipinjam = $totalKoleksiAktif > 0 ? round(($bukuDipinjam / $totalKoleksiAktif) * 100) : 0;
    @endphp

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-6">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                <div class=" border border-emerald-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total anggota</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($totalAnggota) }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ number_format($totalAdmin) }} admin mendukung operasional.</p>
                </div>

                <div class=" border border-sky-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Judul buku</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($totalBuku) }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ number_format($totalStokBuku) }} stok tersedia di rak.</p>
                </div>

                <div class=" border border-amber-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Peminjaman aktif</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($peminjamanAktif) }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ number_format($peminjamanTerlambat) }} transaksi perlu perhatian.</p>
                </div>

                <div class=" border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Buku sedang dipinjam</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($bukuDipinjam) }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ $persentaseDipinjam }}% dari total koleksi aktif sedang keluar.</p>
                </div>

                <div class=" border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Peminjaman selesai</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($peminjamanSelesai) }}</p>
                    <p class="mt-2 text-sm text-gray-500">Total riwayat transaksi yang sudah dikembalikan.</p>
                </div>

                <div class=" border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Stok menipis</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($bukuStokMenipis) }}</p>
                    <p class="mt-2 text-sm text-gray-500">Judul buku dengan stok 3 eksemplar atau kurang.</p>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,1fr)]">
                <div class=" border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Grafik peminjaman 6 bulan terakhir</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Membantu memantau tren aktivitas peminjaman dari waktu ke waktu.
                            </p>
                        </div>
                        <div class=" bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            {{ number_format($totalTransaksi) }} transaksi
                        </div>
                    </div>

                    <div class="mt-8 flex h-72 items-end gap-3">
                        @foreach ($monthlyBorrowings as $item)
                            @php
                                $barHeight = max(($item['total'] / $chartMax) * 100, 8);
                            @endphp
                            <div class="flex flex-1 flex-col items-center gap-3">
                                <span class="text-sm font-semibold text-gray-700">{{ $item['total'] }}</span>
                                <div class="flex h-52 w-full items-end  bg-gray-100 p-2">
                                    <div class="w-full  bg-gradient-to-t from-emerald-600 to-emerald-400"
                                        style="height: {{ $barHeight }}%"></div>
                                </div>
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-700">{{ $item['label'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['full_label'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-6">
                    <div class=" border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Ringkasan sistem</h3>
                        <div class="mt-6 space-y-5">
                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Komposisi pengguna</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($totalPengguna) }}</span>
                                </div>
                                <div class="h-3 overflow-hidden  bg-gray-100">
                                    <div class="h-full  bg-emerald-500"
                                        style="width: {{ $totalPengguna > 0 ? round(($totalAnggota / $totalPengguna) * 100) : 0 }}%"></div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ number_format($totalAnggota) }} siswa dan {{ number_format($totalAdmin) }} admin.
                                </p>
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Ketersediaan koleksi</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($totalStokBuku) }}</span>
                                </div>
                                <div class="h-3 overflow-hidden  bg-gray-100">
                                    <div class="h-full  bg-sky-500"
                                        style="width: {{ $totalKoleksiAktif > 0 ? round(($totalStokBuku / $totalKoleksiAktif) * 100) : 0 }}%"></div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ number_format($bukuDipinjam) }} buku sedang dipinjam dari total koleksi aktif.
                                </p>
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Status transaksi selesai</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($peminjamanSelesai) }}</span>
                                </div>
                                <div class="h-3 overflow-hidden  bg-gray-100">
                                    <div class="h-full  bg-amber-500"
                                        style="width: {{ $totalTransaksi > 0 ? round(($peminjamanSelesai / $totalTransaksi) * 100) : 0 }}%"></div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ number_format($peminjamanAktif) }} peminjaman masih berjalan saat ini.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class=" border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Akses cepat</h3>
                        <div class="mt-4 grid gap-3">
                            <a href="{{ route('admin.users.index') }}"
                                class=" border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700">
                                Kelola data anggota
                            </a>
                            <a href="{{ route('admin.books.index') }}"
                                class=" border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                                Kelola data buku
                            </a>
                            <a href="{{ route('admin.transactions.index') }}"
                                class=" border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700">
                                Cek transaksi peminjaman
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Peminjaman terbaru</h3>
                        <p class="mt-1 text-sm text-gray-500">Lima aktivitas terakhir untuk membantu monitoring harian.</p>
                    </div>
                    <a href="{{ route('admin.transactions.index') }}"
                        class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        Lihat semua transaksi
                    </a>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Anggota
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Tanggal pinjam
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Jatuh tempo
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($recentBorrowings as $borrowing)
                                <tr class="text-sm text-gray-700">
                                    <td class="px-4 py-4 font-medium text-gray-900">
                                        {{ $borrowing->user?->name ?? 'Pengguna tidak ditemukan' }}
                                    </td>
                                    <td class="px-4 py-4 text-gray-600">
                                        {{ \Illuminate\Support\Carbon::parse($borrowing->borrowed_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-gray-600">
                                        {{ $borrowing->due_date ? \Illuminate\Support\Carbon::parse($borrowing->due_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-4">
                                        @php
                                            $statusClass = match ($borrowing->status) {
                                                'returned' => 'bg-sky-100 text-sky-700',
                                                'borrowed' => 'bg-amber-100 text-amber-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span class="inline-flex  px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                            {{ ucfirst($borrowing->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-500">
                                        Belum ada data peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
