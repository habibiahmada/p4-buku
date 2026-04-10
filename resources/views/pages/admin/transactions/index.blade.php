<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <h2 class="section-kicker text-xl">
                    <x-heroicon-o-squares-2x2 class="h-6 w-6" />
                    Kelola Peminjaman
                </h2>
            </div>
        </div>
    </x-slot>

    @php
        $borrowings = $borrowings ?? collect();
        $transactionData = $borrowings;
        $transactionItems = collect(
            method_exists($transactionData, 'items') ? $transactionData->items() : $transactionData,
        );
        $today = now()->startOfDay();

        $countBooks = function ($transaction) {
            return data_get($transaction, 'total_buku') ??
                (data_get($transaction, 'total_books') ??
                    (data_get($transaction, 'borrow_details_sum_qty') ??
                        collect(data_get($transaction, 'borrowDetails', []))->sum('qty')));
        };

        $isOverdue = function ($transaction) use ($today) {
            $status = data_get($transaction, 'status');
            $dueDate = data_get($transaction, 'due_date');

            return $status !== 'returned' &&
                filled($dueDate) &&
                \Illuminate\Support\Carbon::parse($dueDate)->startOfDay()->lt($today);
        };

        $totalPeminjaman = $totalPeminjaman ?? $transactionItems->count();
        $peminjamanAktif = $peminjamanAktif ?? $transactionItems->where('status', 'borrowed')->count();
        $peminjamanSelesai = $peminjamanSelesai ?? $transactionItems->where('status', 'returned')->count();
        $peminjamanTerlambat = $peminjamanTerlambat ?? $transactionItems->filter($isOverdue)->count();
        $totalBukuDipinjam = $totalBukuDipinjam ?? $transactionItems->sum($countBooks);
    @endphp

    @session('success')
        <div class="mb-4 rounded-lg bg-green-50 p-4 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <x-heroicon-o-check-circle class="h-5 w-5 text-green-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endsession

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-5">
                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Peminjaman</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalPeminjaman }}</dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Peminjaman Aktif</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $peminjamanAktif }}</dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Peminjaman Selesai</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $peminjamanSelesai }}</dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Peminjaman Terlambat</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $peminjamanTerlambat }}</dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Buku Dipinjam</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalBukuDipinjam }}</dd>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Daftar Peminjaman</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Kelola informasi transaksi peminjaman perpustakaan di sini.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm">
                <form method="GET" action="{{ route('admin.transactions.index') }}"
                    class="grid gap-4 p-6 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_220px_auto] md:items-end">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Nama Anggota</label>
                        <input id="name" name="name" type="text" value="{{ request('name') }}"
                            placeholder="Cari nama anggota"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

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
                            <option value="borrowed" @selected(request('status') === 'borrowed')>Aktif</option>
                            <option value="returned" @selected(request('status') === 'returned')>Selesai</option>
                            <option value="overdue" @selected(request('status') === 'overdue')>Terlambat</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button type="submit" class="justify-center">
                            Filter
                        </x-primary-button>

                        @if (request()->filled('name') ||
                                request()->filled('from_date') ||
                                request()->filled('to_date') ||
                                request()->filled('status'))
                            <a href="{{ route('admin.transactions.index') }}"
                                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="pb-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    No</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Anggota</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Tanggal Pinjam</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Jatuh Tempo</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Tanggal Kembali</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Total Buku</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Denda</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Status</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($transactionItems as $transaction)
                                @php
                                    $transactionOverdue = $isOverdue($transaction);
                                    $bookCount = $countBooks($transaction);
                                    $statusLabel =
                                        data_get($transaction, 'status') === 'returned'
                                            ? 'Selesai'
                                            : ($transactionOverdue
                                                ? 'Terlambat'
                                                : 'Aktif');
                                    $statusClass =
                                        data_get($transaction, 'status') === 'returned'
                                            ? 'bg-emerald-100 text-emerald-800'
                                            : ($transactionOverdue
                                                ? 'bg-rose-100 text-rose-800'
                                                : 'bg-amber-100 text-amber-800');
                                @endphp
                                <tr class="text-sm text-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ method_exists($transactionData, 'currentPage') && method_exists($transactionData, 'perPage')
                                            ? ($transactionData->currentPage() - 1) * $transactionData->perPage() + $loop->iteration
                                            : $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ data_get($transaction, 'user.name', data_get($transaction, 'user_name', '-')) }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        {{ filled(data_get($transaction, 'borrowed_date'))
                                            ? \Illuminate\Support\Carbon::parse(data_get($transaction, 'borrowed_date'))->format('d M Y')
                                            : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        {{ filled(data_get($transaction, 'due_date'))
                                            ? \Illuminate\Support\Carbon::parse(data_get($transaction, 'due_date'))->format('d M Y')
                                            : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        {{ filled(data_get($transaction, 'returned_date'))
                                            ? \Illuminate\Support\Carbon::parse(data_get($transaction, 'returned_date'))->format('d M Y')
                                            : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $bookCount }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        Rp{{ number_format((float) data_get($transaction, 'charge', 0), 0, ',', '.') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <a href="{{ route('admin.transactions.show', ['transaction' => data_get($transaction, 'id')]) }}"
                                            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            Lihat
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                                        Data peminjaman tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($transactionData, 'links'))
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $transactionData->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

</x-app-layout>
