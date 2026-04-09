<x-app-layout>
    @php
        $today = now()->startOfDay();
        $dailyFine = (int) config('borrowing.daily_fine', 10000);
        $routeTransaction = request()->route('transaction');
        $routeTransactionId = is_object($routeTransaction) ? data_get($routeTransaction, 'id') : $routeTransaction;

        // Set selectedBorrowingId: gunakan old value jika ada (dari form submission),
        // else gunakan ID dari route parameter (saat edit spesifik dari tabel),
        // else kosong (saat masuk via return route)
        $selectedBorrowingId = old('borrowing_id', $borrow?->id ?? ($routeTransactionId ?? ''));

        $returnedDate = now()->format('Y-m-d');
        $returnedDateLabel = now()->translatedFormat('d M Y');

        $borrowings = \App\Models\Borrow::with(['borrowDetails.book'])
            ->when(
                auth()->id(),
                fn($query, $userId) => $query->where('user_id', $userId),
                fn($query) => $query->whereRaw('1 = 0'),
            )
            ->where('status', 'borrowed')
            ->orderBy('due_date')
            ->get()
            ->map(function ($borrowing) use ($today) {
                $dueDate = \Illuminate\Support\Carbon::parse($borrowing->due_date)->startOfDay();

                return [
                    'id' => $borrowing->id,
                    'borrowed_date' => \Illuminate\Support\Carbon::parse($borrowing->borrowed_date)->format('Y-m-d'),
                    'borrowed_date_label' => \Illuminate\Support\Carbon::parse($borrowing->borrowed_date)->format(
                        'd M Y',
                    ),
                    'due_date' => $dueDate->format('Y-m-d'),
                    'due_date_label' => $dueDate->format('d M Y'),
                    'total_books' => (int) collect($borrowing->borrowDetails)->sum('qty'),
                    'is_overdue' => $dueDate->lt($today),
                    'books' => $borrowing->borrowDetails
                        ->map(
                            fn($detail) => [
                                'title' => data_get($detail, 'book.title', 'Buku tidak tersedia'),
                                'qty' => (int) $detail->qty,
                            ],
                        )
                        ->values()
                        ->all(),
                ];
            })
            ->values();

        $hasUpdateRoute = \Illuminate\Support\Facades\Route::has('siswa.transactions.update');
        $updateUrlTemplate = $hasUpdateRoute
            ? route('siswa.transactions.update', ['transaction' => '__BORROWING__'])
            : '#';
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:tracking-tight">
                    Pengembalian Buku
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Pilih pinjaman aktif, cek denda keterlambatan, lalu kembalikan buku.
                </p>
            </div>
            <a href="{{ route('siswa.transactions.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <x-heroicon-o-arrow-left class="mr-2 h-4 w-4" />
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="bg-gradient-to-b from-gray-50 to-white py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border-l-4 border-red-500 bg-red-50 p-4 shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-exclamation-circle class="h-5 w-5 text-red-500" />
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terjadi Kesalahan Form</h3>
                            <ul class="mt-2 list-inside space-y-1 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>&bull; {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" x-data="returnTransactionForm()" :action="formAction"
                class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                @csrf
                @if ($hasUpdateRoute)
                    @method('PUT')
                @endif

                <div class="space-y-6 lg:col-span-8">
                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                            <h3 class="text-base font-semibold text-gray-900">Pilih Pinjaman</h3>
                        </div>
                        <div class="space-y-6 px-6 py-5">
                            <div>
                                <label for="borrowing_id" class="block text-sm font-medium text-gray-700">
                                    Pinjaman Aktif <span class="text-red-500">*</span>
                                </label>
                                <select id="borrowing_id" name="borrowing_id" x-model="selectedBorrowingId"
                                    value="{{ $selectedBorrowingId }}"
                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('borrowing_id') border-red-500 @enderror">
                                    <option value="">Pilih pinjaman yang akan dikembalikan</option>
                                    <template x-for="borrowing in borrowings" :key="borrowing.id">
                                        <option :value="String(borrowing.id)"
                                            :selected="String(borrowing.id) === @js((string) $selectedBorrowingId)"
                                            x-text="`#${borrowing.id} - ${borrowing.borrowed_date_label} / jatuh tempo ${borrowing.due_date_label}`">
                                        </option>
                                    </template>
                                </select>
                                @error('borrowing_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Tanggal
                                    Pengembalian</p>
                                <p class="mt-2 text-sm font-semibold text-gray-900">{{ $returnedDateLabel }}</p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tanggal kembali diisi otomatis sesuai tanggal hari ini.
                                </p>
                            </div>

                            <div x-show="borrowings.length === 0"
                                class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/70 p-8 text-center">
                                <x-heroicon-o-book-open class="mx-auto h-12 w-12 text-gray-300" />
                                <p class="mt-3 text-sm font-medium text-gray-900">Tidak ada pinjaman aktif</p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Semua buku Anda sudah dikembalikan atau belum ada transaksi peminjaman.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                            <h3 class="text-base font-semibold text-gray-900">Detail Pinjaman</h3>
                        </div>
                        <div class="px-6 py-5" x-show="selectedBorrowing">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Tanggal Pinjam
                                    </p>
                                    <p class="mt-2 text-sm font-semibold text-gray-900"
                                        x-text="selectedBorrowing?.borrowed_date_label"></p>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Jatuh Tempo</p>
                                    <p class="mt-2 text-sm font-semibold text-gray-900"
                                        x-text="selectedBorrowing?.due_date_label"></p>
                                </div>
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total Buku</p>
                                    <p class="mt-2 text-sm font-semibold text-gray-900"
                                        x-text="`${selectedBorrowing?.total_books ?? 0} Buku`"></p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-900">Daftar Buku</h4>
                                <div class="mt-3 space-y-3">
                                    <template x-for="book in selectedBorrowing?.books ?? []"
                                        :key="`${selectedBorrowing?.id}-${book.title}`">
                                        <div
                                            class="flex items-center justify-between rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900" x-text="book.title"></p>
                                                <p class="mt-1 text-xs text-gray-500">Buku dalam transaksi terpilih</p>
                                            </div>
                                            <span
                                                class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700"
                                                x-text="`Qty ${book.qty}`"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div x-show="!selectedBorrowing" class="px-6 py-10 text-center text-sm text-gray-500">
                            Pilih pinjaman terlebih dahulu untuk melihat rincian buku dan perhitungan pengembalian.
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 lg:mt-0">
                    <div
                        class="sticky top-6 overflow-hidden rounded-xl border border-indigo-100 bg-indigo-50 shadow-sm">
                        <div class="border-b border-indigo-100/50 px-6 py-5">
                            <h3 class="text-lg font-semibold text-indigo-900">Ringkasan Pengembalian</h3>
                            <p class="mt-1 text-sm text-indigo-700">Denda dihitung otomatis {{ 'Rp' . number_format($dailyFine, 0, ',', '.') }} per hari
                                keterlambatan.</p>
                        </div>
                        <div class="space-y-4 px-6 py-5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-indigo-800">ID Pinjaman</span>
                                <span class="font-medium text-indigo-900"
                                    x-text="selectedBorrowing ? `#${selectedBorrowing.id}` : '-'"></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-indigo-800">Jumlah Buku</span>
                                <span class="font-medium text-indigo-900"
                                    x-text="selectedBorrowing ? `${selectedBorrowing.total_books} Buku` : '-'"></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-indigo-800">Terlambat</span>
                                <span class="font-medium text-indigo-900" x-text="`${overdueDays} Hari`"></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-indigo-800">Denda per Hari</span>
                                <span class="font-medium text-indigo-900" x-text="formatCurrency(dailyFine)"></span>
                            </div>
                            <div class="flex items-center justify-between border-t border-indigo-200/50 pt-4">
                                <span class="text-sm font-semibold text-indigo-900">Total Denda</span>
                                <span class="text-lg font-bold text-indigo-900"
                                    x-text="formatCurrency(totalFine)"></span>
                            </div>

                            <div class="rounded-xl border border-indigo-200 bg-white/60 p-4 text-sm text-indigo-900">
                                Buku akan ditandai kembali pada tanggal <span
                                    class="font-semibold">{{ $returnedDateLabel }}</span>.
                            </div>

                            <div class="pt-2">
                                <button type="submit" :disabled="!canSubmit"
                                    class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-indigo-300 disabled:shadow-none">
                                    Kembalikan Buku
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="returned_date" value="{{ $returnedDate }}">
                <input type="hidden" name="charge" :value="totalFine">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('returnTransactionForm', () => ({
                borrowings: @js($borrowings),
                selectedBorrowingId: @js($selectedBorrowingId ? (string) $selectedBorrowingId : ''),
                returnedDate: @js($returnedDate),
                dailyFine: {{ $dailyFine }},
                updateUrlTemplate: @js($updateUrlTemplate),
                hasUpdateRoute: @js($hasUpdateRoute),

                init() {
                    // Ensure selectedBorrowingId is synced with select element value on init
                    this.$nextTick(() => {
                        const selectElement = document.getElementById('borrowing_id');
                        if (selectElement && this.selectedBorrowingId) {
                            selectElement.value = String(this.selectedBorrowingId);
                        }
                    });
                },

                get selectedBorrowing() {
                    return this.borrowings.find(borrowing => String(borrowing.id) === String(this
                        .selectedBorrowingId)) ?? null;
                },

                get overdueDays() {
                    if (!this.selectedBorrowing || !this.returnedDate) {
                        return 0;
                    }

                    const dueDate = new Date(`${this.selectedBorrowing.due_date}T00:00:00`);
                    const returnedDate = new Date(`${this.returnedDate}T00:00:00`);
                    const millisecondsPerDay = 1000 * 60 * 60 * 24;
                    const diffDays = Math.floor((returnedDate - dueDate) / millisecondsPerDay);

                    return diffDays > 0 ? diffDays : 0;
                },

                get totalFine() {
                    return this.overdueDays * this.dailyFine;
                },

                get formAction() {
                    if (!this.hasUpdateRoute || !this.selectedBorrowingId) {
                        return '#';
                    }

                    return this.updateUrlTemplate.replace('__BORROWING__', this
                        .selectedBorrowingId);
                },

                get canSubmit() {
                    return this.hasUpdateRoute && !!this.selectedBorrowingId;
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                    }).format(value || 0);
                },
            }));
        });
    </script>
</x-app-layout>
