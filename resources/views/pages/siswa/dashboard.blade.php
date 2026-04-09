<x-app-layout>
    @php
        $today = now()->startOfDay();
        $userId = auth()->id();

        $baseQuery = \App\Models\Borrow::with(['borrowDetails.book'])->when(
            $userId,
            fn($query, $id) => $query->where('user_id', $id),
            fn($query) => $query->whereRaw('1 = 0'),
        );

        $allBorrowings = (clone $baseQuery)->orderByDesc('borrowed_date')->get();
        $countBooks = fn($transaction) => collect($transaction->borrowDetails)->sum('qty');
        $isOverdue = fn($transaction) => $transaction->status !== 'returned' &&
            filled($transaction->due_date) &&
            \Illuminate\Support\Carbon::parse($transaction->due_date)->startOfDay()->lt($today);

        $totalTransactions = $allBorrowings->count();
        $activeTransactions = $allBorrowings->where('status', 'borrowed')->count();
        $returnedTransactions = $allBorrowings->where('status', 'returned')->count();
        $overdueTransactions = $allBorrowings->filter($isOverdue)->count();
        $activeBooks = $allBorrowings->where('status', 'borrowed')->sum($countBooks);
        $returnedBooks = $allBorrowings->where('status', 'returned')->sum($countBooks);
        $completionRate = $totalTransactions > 0 ? round(($returnedTransactions / $totalTransactions) * 100) : 0;
        $recentBorrowings = $allBorrowings->take(5);

        $monthlyBorrowings = collect(range(5, 0))->map(function ($offset) use ($allBorrowings) {
            $month = now()->startOfMonth()->subMonths($offset);

            return [
                'label' => $month->translatedFormat('M'),
                'full_label' => $month->translatedFormat('F Y'),
                'total' => $allBorrowings
                    ->filter(fn($transaction) => filled($transaction->borrowed_date))
                    ->filter(
                        fn($transaction) => \Illuminate\Support\Carbon::parse($transaction->borrowed_date)->format('Y-m') ===
                            $month->format('Y-m'),
                    )
                    ->count(),
            ];
        });

        $chartMax = max($monthlyBorrowings->max('total'), 1);
        $hasCreateRoute = \Illuminate\Support\Facades\Route::has('siswa.transactions.create');
        $hasIndexRoute = \Illuminate\Support\Facades\Route::has('siswa.transactions.index');
        $hasReturnRoute = \Illuminate\Support\Facades\Route::has('siswa.transactions.return');
        $hasEditRoute = \Illuminate\Support\Facades\Route::has('siswa.transactions.edit');
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <span class="section-kicker">
                    <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                    Siswa Dashboard
                </span>
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-900">
                        Ringkasan peminjaman buku Anda
                    </h1>
                    <p class="max-w-3xl text-sm text-gray-600 sm:text-base">
                        Lihat statistik peminjaman, progres pengembalian, dan akses cepat untuk meminjam atau
                        mengembalikan buku.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                @if ($hasCreateRoute)
                    <a href="{{ route('siswa.transactions.create') }}"
                        class="inline-flex items-center justify-center bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                        Pinjam Buku
                    </a>
                @endif

                @if ($hasReturnRoute)
                    <a href="{{ route('siswa.transactions.return') }}"
                        class="inline-flex items-center justify-center border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                        Kembalikan Buku
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-6">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="border border-emerald-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Total transaksi</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($totalTransactions) }}</p>
                    <p class="mt-2 text-sm text-gray-500">Semua riwayat peminjaman yang pernah Anda buat.</p>
                </div>

                <div class="border border-amber-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Sedang dipinjam</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($activeBooks) }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ number_format($activeTransactions) }} transaksi masih aktif.</p>
                </div>

                <div class="border border-sky-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Sudah dikembalikan</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($returnedBooks) }}</p>
                    <p class="mt-2 text-sm text-gray-500">{{ number_format($returnedTransactions) }} transaksi sudah selesai.</p>
                </div>

                <div class="border border-rose-100 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Terlambat</p>
                    <p class="mt-3 text-3xl font-semibold text-gray-900">{{ number_format($overdueTransactions) }}</p>
                    <p class="mt-2 text-sm text-gray-500">Segera lakukan pengembalian agar tidak terkena denda tambahan.</p>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,1fr)]">
                <div class="border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Grafik peminjaman 6 bulan terakhir</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Ringkasan frekuensi peminjaman Anda dari bulan ke bulan.
                            </p>
                        </div>
                        <div class="bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                            {{ number_format($totalTransactions) }} transaksi
                        </div>
                    </div>

                    <div class="mt-8 flex h-72 items-end gap-3">
                        @foreach ($monthlyBorrowings as $item)
                            @php
                                $barHeight = max(($item['total'] / $chartMax) * 100, 8);
                            @endphp
                            <div class="flex flex-1 flex-col items-center gap-3">
                                <span class="text-sm font-semibold text-gray-700">{{ $item['total'] }}</span>
                                <div class="flex h-52 w-full items-end bg-gray-100 p-2">
                                    <div class="w-full bg-gradient-to-t from-emerald-600 to-emerald-400"
                                        style="height: {{ $item['total'] > 0 ? $barHeight : 8 }}%"></div>
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
                    <div class="border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Progres aktivitas</h3>
                        <div class="mt-6 space-y-5">
                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Pengembalian selesai</span>
                                    <span class="font-semibold text-gray-900">{{ $completionRate }}%</span>
                                </div>
                                <div class="h-3 overflow-hidden bg-gray-100">
                                    <div class="h-full bg-emerald-500" style="width: {{ $completionRate }}%"></div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ number_format($returnedTransactions) }} dari {{ number_format($totalTransactions) }}
                                    transaksi telah selesai.
                                </p>
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Transaksi aktif</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($activeTransactions) }}</span>
                                </div>
                                <div class="h-3 overflow-hidden bg-gray-100">
                                    <div class="h-full bg-amber-500"
                                        style="width: {{ $totalTransactions > 0 ? round(($activeTransactions / $totalTransactions) * 100) : 0 }}%"></div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ number_format($activeBooks) }} buku masih berada dalam pinjaman aktif.
                                </p>
                            </div>

                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Perlu perhatian</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($overdueTransactions) }}</span>
                                </div>
                                <div class="h-3 overflow-hidden bg-gray-100">
                                    <div class="h-full bg-rose-500"
                                        style="width: {{ $activeTransactions > 0 ? round(($overdueTransactions / $activeTransactions) * 100) : 0 }}%"></div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    Transaksi yang melewati jatuh tempo akan muncul di halaman pengembalian.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900">Akses cepat</h3>
                        <div class="mt-4 grid gap-3">
                            @if ($hasCreateRoute)
                                <a href="{{ route('siswa.transactions.create') }}"
                                    class="border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700">
                                    Ajukan peminjaman buku baru
                                </a>
                            @endif

                            @if ($hasReturnRoute)
                                <a href="{{ route('siswa.transactions.return') }}"
                                    class="border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-amber-300 hover:bg-amber-50 hover:text-amber-700">
                                    Lakukan pengembalian buku
                                </a>
                            @endif

                            @if ($hasIndexRoute)
                                <a href="{{ route('siswa.transactions.index') }}"
                                    class="border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 transition hover:border-sky-300 hover:bg-sky-50 hover:text-sky-700">
                                    Lihat semua riwayat transaksi
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Aktivitas terbaru</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Daftar transaksi terbaru untuk memantau status pinjam dan pengembalian buku.
                        </p>
                    </div>
                    @if ($hasIndexRoute)
                        <a href="{{ route('siswa.transactions.index') }}"
                            class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                            Lihat semua transaksi
                        </a>
                    @endif
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Tanggal pinjam
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Buku
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Jatuh tempo
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($recentBorrowings as $transaction)
                                @php
                                    $transactionOverdue = $isOverdue($transaction);
                                    $statusLabel =
                                        $transaction->status === 'returned'
                                            ? 'Sudah Dikembalikan'
                                            : ($transactionOverdue ? 'Terlambat' : 'Sedang Dipinjam');
                                    $statusClass =
                                        $transaction->status === 'returned'
                                            ? 'bg-emerald-100 text-emerald-800'
                                            : ($transactionOverdue
                                                ? 'bg-rose-100 text-rose-800'
                                                : 'bg-amber-100 text-amber-800');
                                @endphp
                                <tr class="text-sm text-gray-700">
                                    <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                        {{ filled($transaction->borrowed_date) ? \Illuminate\Support\Carbon::parse($transaction->borrowed_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="space-y-1">
                                            @forelse ($transaction->borrowDetails as $detail)
                                                <div class="text-gray-900">
                                                    {{ data_get($detail, 'book.title', 'Buku tidak tersedia') }}
                                                    <span class="text-gray-500">x{{ $detail->qty }}</span>
                                                </div>
                                            @empty
                                                <span class="text-gray-500">Tidak ada detail buku</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-gray-600">
                                        {{ filled($transaction->due_date) ? \Illuminate\Support\Carbon::parse($transaction->due_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        @if ($transaction->status === 'borrowed' && $hasEditRoute)
                                            <a href="{{ route('siswa.transactions.edit', $transaction->id) }}"
                                                class="inline-flex items-center justify-center border border-gray-300 bg-white px-3 py-1 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                                                Kembalikan
                                            </a>
                                        @else
                                            <span class="text-gray-500">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500">
                                        Belum ada transaksi peminjaman.
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
7