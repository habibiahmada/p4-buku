<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <span class="section-kicker">
                    <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                    Peminjaman Buku
                </span>
            </div>
        </div>
    </x-slot>

    @php
        $today = now()->startOfDay();
        $userId = auth()->id();

        $baseQuery = \App\Models\Borrow::with(['borrowDetails.book'])->when(
            $userId,
            fn($query, $id) => $query->where('user_id', $id),
            fn($query) => $query->whereRaw('1 = 0'),
        );

        $allBorrowings = (clone $baseQuery)->get();

        $filteredQuery = (clone $baseQuery)
            ->when(request('from_date'), fn($query, $date) => $query->whereDate('borrowed_date', '>=', $date))
            ->when(request('to_date'), fn($query, $date) => $query->whereDate('borrowed_date', '<=', $date))
            ->when(request('status'), function ($query, $status) use ($today) {
                if ($status === 'overdue') {
                    $query->where('status', 'borrowed')->whereDate('due_date', '<', $today);

                    return;
                }

                if (in_array($status, ['borrowed', 'returned'], true)) {
                    $query->where('status', $status);
                }
            })
            ->orderByRaw("CASE WHEN status = 'borrowed' THEN 0 ELSE 1 END")
            ->orderByDesc('borrowed_date');

        $transactionData = $filteredQuery->paginate(10)->withQueryString();
        $transactionItems = collect($transactionData->items());

        $countBooks = fn($transaction) => collect($transaction->borrowDetails)->sum('qty');
        $isOverdue = fn($transaction) => $transaction->status !== 'returned' &&
            filled($transaction->due_date) &&
            \Illuminate\Support\Carbon::parse($transaction->due_date)->startOfDay()->lt($today);

        $totalPeminjamanSaya = $allBorrowings->count();
        $totalBukuDipinjam = $allBorrowings->where('status', 'borrowed')->sum($countBooks);
        $totalBukuDikembalikan = $allBorrowings->where('status', 'returned')->sum($countBooks);
        $totalBukuTerlambat = $allBorrowings->filter($isOverdue)->sum($countBooks);

        $borrowRouteExists = \Illuminate\Support\Facades\Route::has('siswa.transactions.create');
        $returnRouteExists = \Illuminate\Support\Facades\Route::has('siswa.transactions.return');
    @endphp

    @session('success')
        <div class="mx-auto mb-4 max-w-7xl sm:px-6 lg:px-8">
            <div class="rounded-lg bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-check-circle class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endsession

    <div class="py-6">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Peminjaman Saya</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                        {{ $totalPeminjamanSaya }}
                    </dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Buku Sedang Dipinjam</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                        {{ $totalBukuDipinjam }}
                    </dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Buku Sudah Dikembalikan</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                        {{ $totalBukuDikembalikan }}
                    </dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Buku Terlambat</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">
                        {{ $totalBukuTerlambat }}
                    </dd>
                </div>
            </div>

            <div class="overflow-hidden bg-white shadow-sm">
                <div class="flex flex-col gap-6 p-6 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Daftar Peminjaman Saya</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Pantau status peminjaman, tanggal jatuh tempo, dan riwayat pengembalian buku Anda di sini.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        @if ($borrowRouteExists)
                            <a href="{{ route('siswa.transactions.create') }}"
                                class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Buat Peminjaman
                            </a>
                        @else
                            <button type="button"
                                class="inline-flex items-center justify-center rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-500 shadow-sm"
                                disabled>
                                Buat Peminjaman
                            </button>
                        @endif

                        @if ($returnRouteExists)
                            <a href="{{ route('siswa.transactions.return') }}"
                                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Kembalikan Buku
                            </a>
                        @else
                            <button type="button"
                                class="inline-flex items-center justify-center rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-500 shadow-sm"
                                disabled>
                                Kembalikan Buku
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="overflow-hidden bg-white shadow-sm">
                <form method="GET" action="{{ route('siswa.transactions.index') }}"
                    class="grid gap-4 p-6 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_220px_auto] md:items-end">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Rentang Tanggal</label>
                        <div class="grid gap-2 sm:grid-cols-2">
                            <input id="from_date" name="from_date" type="date" value="{{ request('from_date') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <input id="to_date" name="to_date" type="date" value="{{ request('to_date') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Filter Status</label>
                        <select id="status" name="status"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Semua Status</option>
                            <option value="borrowed" @selected(request('status') === 'borrowed')>Sedang Dipinjam</option>
                            <option value="returned" @selected(request('status') === 'returned')>Sudah Dikembalikan</option>
                            <option value="overdue" @selected(request('status') === 'overdue')>Terlambat</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex gap-3">
                            <x-primary-button type="submit" class="justify-center">
                                Filter
                            </x-primary-button>

                            @if (request()->filled('from_date') || request()->filled('to_date') || request()->filled('status'))
                                <a href="{{ route('siswa.transactions.index') }}"
                                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    No
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Tanggal Pinjam
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Jatuh Tempo
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Buku
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Total
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Denda
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Tanggal Kembali
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($transactionItems as $transaction)
                                @php
                                    $transactionOverdue = $isOverdue($transaction);
                                    $bookCount = $countBooks($transaction);
                                    $statusLabel =
                                        $transaction->status === 'returned'
                                            ? 'Sudah Dikembalikan'
                                            : ($transactionOverdue
                                                ? 'Terlambat'
                                                : 'Sedang Dipinjam');
                                    $statusClass =
                                        $transaction->status === 'returned'
                                            ? 'bg-emerald-100 text-emerald-800'
                                            : ($transactionOverdue
                                                ? 'bg-rose-100 text-rose-800'
                                                : 'bg-amber-100 text-amber-800');
                                @endphp
                                <tr class="text-sm text-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ ($transactionData->currentPage() - 1) * $transactionData->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        {{ filled($transaction->borrowed_date) ? \Illuminate\Support\Carbon::parse($transaction->borrowed_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        {{ filled($transaction->due_date) ? \Illuminate\Support\Carbon::parse($transaction->due_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
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
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $bookCount }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        Rp{{ number_format((float) $transaction->charge, 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        {{ filled($transaction->returned_date) ? \Illuminate\Support\Carbon::parse($transaction->returned_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($transaction->status === 'borrowed')
                                            <a href="{{ route('siswa.transactions.edit', $transaction->id) }}"
                                                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                                Kembalikan
                                            </a>
                                        @else
                                            <span class="text-gray-500">Tidak ada aksi</span>
                                        @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                                        Belum ada transaksi peminjaman yang sesuai dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $transactionData->links() }}
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
