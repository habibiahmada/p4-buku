<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:tracking-tight">
                    Buat Peminjaman Baru
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Cari buku yang ingin dipinjam dan atur durasi peminjaman Anda.
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

            {{-- Alert Error Validation --}}
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

            <form action="{{ route('siswa.transactions.store') }}" method="POST" x-data="transactionForm()"
                @submit="handleSubmit($event)" class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                @csrf

                {{-- Kolom Kiri: Form Utama --}}
                <div class="space-y-6 lg:col-span-8">
                    {{-- Section 1: Tanggal Peminjaman --}}
                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                            <h3 class="flex items-center text-base font-semibold text-gray-900">
                                Periode Peminjaman
                            </h3>
                        </div>
                        <div class="px-6 py-5">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="borrowed_date" class="block text-sm font-medium text-gray-700">
                                        Tanggal Pinjam <span class="text-red-500">*</span>
                                    </label>
                                    <input id="borrowed_date" name="borrowed_date" type="date"
                                        value="{{ old('borrowed_date', now()->format('Y-m-d')) }}"
                                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('borrowed_date') border-red-500 @enderror">
                                    @error('borrowed_date')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="due_date" class="block text-sm font-medium text-gray-700">
                                        Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                                    </label>
                                    <input id="due_date" name="due_date" type="date"
                                        value="{{ old('due_date', now()->addDays(7)->format('Y-m-d')) }}"
                                        class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('due_date') border-red-500 @enderror">
                                    @error('due_date')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Pilih Buku --}}
                    <div class="overflow-visible rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4">
                            <h3 class="flex items-center text-base font-semibold text-gray-900">
                                Daftar Buku yang Dipinjam
                            </h3>
                        </div>
                        <div class="px-6 py-5">

                            {{-- Autocomplete Search Input --}}
                            <div class="relative rounded-xl border border-gray-200 bg-gray-50/60 p-4"
                                @click.outside="showDropdown = false">
                                <label class="block text-sm font-medium text-gray-700">Cari & Tambah Buku</label>
                                <p class="mt-1 text-sm text-gray-500">
                                    Gunakan judul, penulis, atau penerbit untuk menemukan buku lebih cepat.
                                </p>
                                <div class="relative mt-2">
                                    <input x-ref="searchInput" x-model="search" @input="showDropdown = true"
                                        @focus="showDropdown = true" type="text"
                                        placeholder="Ketik judul, penulis, atau penerbit buku..."
                                        class="block w-full rounded-2xl border border-gray-300 bg-white py-3 pl-10 pr-4 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                {{-- Dropdown Hasil Pencarian --}}
                                <div x-show="showDropdown" x-transition x-cloak
                                    class="absolute inset-x-0 top-full z-50 mt-2 w-full overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl">
                                    <ul class="max-h-60 overflow-y-auto py-1">
                                        <template x-for="book in filteredBooks" :key="book.id">
                                            <li>
                                                <button type="button" @click="addBook(book)"
                                                    class="flex w-full items-center justify-between px-4 py-3 text-left transition hover:bg-indigo-50 focus:bg-indigo-50 focus:outline-none">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900"
                                                            x-text="book.title"></p>
                                                        <p class="text-xs text-gray-500"
                                                            x-text="book.author + ' - ' + book.publisher"></p>
                                                    </div>
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800"
                                                        x-text="'Stok: ' + book.stock">
                                                    </span>
                                                </button>
                                            </li>
                                        </template>
                                        <li x-show="filteredBooks.length === 0 && search.trim() !== ''"
                                            class="px-4 py-3 text-center text-sm text-gray-500">
                                            Buku tidak ditemukan.
                                        </li>
                                        <li x-show="filteredBooks.length === 0 && search.trim() === ''"
                                            class="px-4 py-3 text-center text-sm text-gray-500">
                                            Ketik untuk mencari buku...
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Daftar Keranjang Buku (List Buku Terpilih) --}}
                            <div class="mt-4">
                                <div class="mb-3 flex items-center justify-between" x-show="books_list.length > 0">
                                    <h4 class="text-sm font-medium text-gray-900">Buku Terpilih</h4>
                                    <span
                                        class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700"
                                        x-text="books_list.length + ' judul dipilih'"></span>
                                </div>

                                <div class="space-y-3">
                                    <template x-for="(book, index) in books_list" :key="book.id">
                                        <div
                                            class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-gradient-to-r from-white to-gray-50 p-4 shadow-sm transition-all sm:flex-row sm:items-center sm:justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900" x-text="book.title"></p>
                                                <p class="text-xs text-gray-500" x-text="book.author"></p>
                                                <p class="mt-1 text-xs text-indigo-600"
                                                    x-text="'Stok tersedia: ' + book.stock"></p>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                {{-- Kontrol Jumlah (Qty) --}}
                                                <div class="flex items-center rounded-lg border border-gray-300">
                                                    <button type="button" @click="updateQty(index, -1)"
                                                        class="rounded-l-lg px-3 py-1.5 text-gray-600 transition hover:bg-gray-100 focus:outline-none">
                                                        -
                                                    </button>
                                                    <input type="number" x-model.number="book.qty"
                                                        @change="validateQty(index)"
                                                        class="w-14 border-0 p-0 text-center text-sm font-medium text-gray-900 focus:ring-0 [-moz-appearance:_textfield] [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none">
                                                    <button type="button" @click="updateQty(index, 1)"
                                                        class="rounded-r-lg px-3 py-1.5 text-gray-600 transition hover:bg-gray-100 focus:outline-none">
                                                        +
                                                    </button>
                                                </div>

                                                {{-- Tombol Hapus --}}
                                                <button type="button" @click="removeBook(index)" title="Hapus buku"
                                                    class="rounded-md p-2 text-red-500 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                                    <x-heroicon-o-trash class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Empty State --}}
                                    <div x-show="books_list.length === 0" x-transition
                                        class="rounded-xl border-2 border-dashed border-gray-300 bg-gray-50/70 p-8 text-center">
                                        <x-heroicon-o-book-open class="mx-auto h-12 w-12 text-gray-300" />
                                        <p class="mt-3 text-sm font-medium text-gray-900">Belum ada buku dipilih</p>
                                        <p class="mt-1 text-sm text-gray-500">Cari buku melalui kolom pencarian di atas
                                            untuk menambahkannya ke daftar ini.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Ringkasan --}}
                <div class="lg:col-span-4 lg:mt-0">
                    <div
                        class="sticky top-6 overflow-hidden rounded-xl border border-indigo-100 bg-indigo-50 shadow-sm">
                        <div class="border-b border-indigo-100/50 px-6 py-5">
                            <h3 class="text-lg font-semibold text-indigo-900">Ringkasan</h3>
                            <p class="mt-1 text-sm text-indigo-700">Pastikan buku dan tanggal pinjam sudah sesuai.</p>
                        </div>
                        <div class="space-y-4 px-6 py-5">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-indigo-800">Total Jenis Buku</span>
                                <span class="font-medium text-indigo-900"
                                    x-text="books_list.length + ' Jenis'"></span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-indigo-800">Total Kuantitas</span>
                                <span class="font-semibold text-indigo-900" x-text="getTotalBooks() + ' Buku'"></span>
                            </div>
                            <div class="flex items-center justify-between border-t border-indigo-200/50 pt-4 text-sm">
                                <span class="text-indigo-800">Durasi Peminjaman</span>
                                <span class="font-semibold text-indigo-900" x-text="getDuration() + ' Hari'"></span>
                            </div>

                            <div class="mt-6 pt-4">
                                <button type="submit" :disabled="books_list.length === 0"
                                    class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-indigo-300 disabled:shadow-none">
                                    Simpan Peminjaman
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Input Hidden Array agar bisa dikirim ke Laravel Backend persis seperti aslinya --}}
                <div class="hidden">
                    <template x-for="(book, index) in books_list" :key="`input-${book.id}`">
                        <div>
                            <input type="hidden" :name="`books[${index}][id]`" :value="book.id">
                            <input type="hidden" :name="`books[${index}][qty]`" :value="book.qty">
                        </div>
                    </template>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tambah Buku --}}
    <div x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showModal = false"
                aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Pilih Buku
                            </h3>
                            <div class="mt-4">
                                <input x-ref="modalSearchInput" x-model="modalSearch" type="text"
                                    placeholder="Cari buku..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <ul class="mt-4 max-h-60 overflow-y-auto">
                                    <template x-for="book in modalFilteredBooks" :key="book.id">
                                        <li>
                                            <button type="button" @click="addBookFromModal(book)"
                                                class="flex w-full items-center justify-between px-4 py-3 text-left transition hover:bg-indigo-50 focus:bg-indigo-50 focus:outline-none">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900" x-text="book.title">
                                                    </p>
                                                    <p class="text-xs text-gray-500"
                                                        x-text="book.author + ' - ' + book.publisher"></p>
                                                </div>
                                                <span
                                                    class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800"
                                                    x-text="'Stok: ' + book.stock"></span>
                                            </button>
                                        </li>
                                    </template>
                                    <li x-show="modalFilteredBooks.length === 0"
                                        class="px-4 py-3 text-center text-sm text-gray-500">
                                        Buku tidak ditemukan.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('transactionForm', () => ({
                // Injeksi data buku dari PHP
                books: @js(
    $books
        ->map(
            fn($book) => [
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'publisher' => $book->publisher,
                'stock' => $book->stock,
            ],
        )
        ->toArray(),
),

                search: '',
                showDropdown: false,
                books_list: [],
                showModal: false,
                modalSearch: '',
                modalFilteredBooks: [],

                // Filter buku berdasarkan pencarian
                get filteredBooks() {
                    const q = this.search.toLowerCase().trim();
                    if (q === '') return this.books.slice(0,
                        10); // Tampilkan 10 buku pertama saat fokus
                    return this.books.filter(book =>
                        book.title.toLowerCase().includes(q) ||
                        book.author.toLowerCase().includes(q) ||
                        book.publisher.toLowerCase().includes(q)
                    ).slice(0, 10); // Batasi hasil untuk performa & kerapian
                },

                // Filter buku untuk modal
                get modalFilteredBooks() {
                    const q = this.modalSearch.toLowerCase().trim();
                    if (q === '') return this.books.slice(0,
                        20); // Tampilkan 20 buku pertama di modal
                    return this.books.filter(book =>
                        book.title.toLowerCase().includes(q) ||
                        book.author.toLowerCase().includes(q) ||
                        book.publisher.toLowerCase().includes(q)
                    ).slice(0, 20);
                },

                // Tambahkan buku dari dropdown
                addBook(book) {
                    if (book.stock < 1) {
                        alert('Maaf, stok buku ini sedang kosong.');
                        return;
                    }

                    const existingIndex = this.books_list.findIndex(b => b.id === book.id);

                    if (existingIndex !== -1) {
                        // Jika sudah ada, tambah qty (jangan lewat stok)
                        if (this.books_list[existingIndex].qty < book.stock) {
                            this.books_list[existingIndex].qty++;
                        }
                    } else {
                        // Jika buku baru, tambahkan dengan qty 1
                        this.books_list.push({
                            ...book,
                            qty: 1
                        });
                    }

                    // Reset search dan fokus kembali jika ingin mengetik cepat
                    this.search = '';
                    this.showDropdown = false;
                    this.$refs.searchInput.focus();
                },

                // Tambahkan buku dari modal
                addBookFromModal(book) {
                    this.addBook(book);
                    this.showModal = false;
                    this.modalSearch = '';
                },

                // Update qty menggunakan tombol +/-
                updateQty(index, delta) {
                    let item = this.books_list[index];
                    let newQty = item.qty + delta;

                    if (newQty >= 1 && newQty <= item.stock) {
                        item.qty = newQty;
                    }
                },

                // Validasi saat user mengetik angka secara manual
                validateQty(index) {
                    let item = this.books_list[index];
                    // Konversi ke integer, jika gagal jadikan 1
                    let value = parseInt(item.qty);

                    if (isNaN(value) || value < 1) {
                        item.qty = 1;
                    } else if (value > item.stock) {
                        item.qty = item.stock;
                    } else {
                        item.qty = value;
                    }
                },

                removeBook(index) {
                    this.books_list.splice(index, 1);
                },

                getTotalBooks() {
                    return this.books_list.reduce((sum, book) => sum + parseInt(book.qty), 0);
                },

                getDuration() {
                    const borrowInput = document.getElementById('borrowed_date').value;
                    const dueInput = document.getElementById('due_date').value;

                    if (!borrowInput || !dueInput) return 0;

                    const borrowDate = new Date(borrowInput);
                    const dueDate = new Date(dueInput);
                    const diffTime = dueDate - borrowDate;
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    return Number.isFinite(diffDays) && diffDays > 0 ? diffDays : 0;
                },

                handleSubmit(event) {
                    if (this.books_list.length === 0) {
                        event.preventDefault();
                        alert('Silakan pilih minimal satu buku untuk meminjam.');
                    }
                }
            }));
        });
    </script>
</x-app-layout>
