<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <h2 class="section-kicker text-xl">
                    <x-heroicon-o-squares-2x2 class="h-6 w-6" />
                    Kelola Buku
                </h2>
            </div>
        </div>
    </x-slot>

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
            <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Buku</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalBuku }}</dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Buku Tersedia</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $bukuTersedia }}</dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Buku Dipinjam</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $bukuDipinjam }}</dd>
                </div>
            </div>
        </div>

    </div>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm flex justify-between items-center">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Daftar Buku</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Kelola informasi buku perpustakaan di sini.
                    </p>
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <x-primary-button onclick="window.location='{{ route('admin.books.create') }}'">
                        <x-heroicon-o-plus class="h-5 w-5" />
                        Tambah Buku
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm">
                <form method="GET" action="{{ route('admin.books.index') }}"
                    class="grid gap-4 p-6 md:grid-cols-[minmax(0,1fr)_220px_auto] md:items-end">
                    <div>
                        <label for="search" class="mb-2 block text-sm font-medium text-gray-700">Cari Buku</label>
                        <input id="search" name="search" type="text" value="{{ request('search') }}"
                            placeholder="Cari judul atau penulis"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div x-data="{
                        open: false,
                        search: '',
                        selected: @js(request('publisher', '')),
                        publishers: @js($publishers->values()->toArray()),
                        get filteredPublishers() {
                            if (!this.search) return this.publishers;
                            const keyword = this.search.toLowerCase();
                            return this.publishers.filter((publisher) => publisher.toLowerCase().includes(keyword));
                        },
                        choose(publisher = '') {
                            this.selected = publisher;
                            this.search = '';
                            this.open = false;
                        }
                    }" class="relative">
                        <label for="publisher" class="mb-2 block text-sm font-medium text-gray-700">Filter
                            Penerbit</label>
                        <input id="publisher" name="publisher" type="hidden" x-model="selected">

                        <button type="button"
                            @click="open = !open; if (open) { $nextTick(() => $refs.publisherSearch.focus()) }"
                            class="flex w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                            <span class="truncate" x-text="selected || 'Semua Penerbit'"></span>
                            <span class="ml-3 text-gray-400">v</span>
                        </button>

                        <div x-show="open" x-cloak @click.outside="open = false"
                            class="absolute z-10 mt-2 w-full rounded-md border border-gray-200 bg-white shadow-lg">
                            <div class="border-b border-gray-100 p-2">
                                <input x-ref="publisherSearch" x-model="search" type="text"
                                    placeholder="Cari penerbit..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="max-h-60 overflow-y-auto py-1">
                                <button type="button" @click="choose('')"
                                    class="block w-full px-3 py-2 text-left text-sm transition hover:bg-gray-50"
                                    :class="{ 'bg-indigo-50 text-indigo-700': selected === '' }">
                                    Semua Penerbit
                                </button>

                                <template x-for="publisher in filteredPublishers" :key="publisher">
                                    <button type="button" @click="choose(publisher)"
                                        class="block w-full px-3 py-2 text-left text-sm transition hover:bg-gray-50"
                                        :class="{ 'bg-indigo-50 text-indigo-700': selected === publisher }"
                                        x-text="publisher"></button>
                                </template>

                                <p x-show="filteredPublishers.length === 0" class="px-3 py-2 text-sm text-gray-500">
                                    Penerbit tidak ditemukan.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button type="submit" class="justify-center">
                            Filter
                        </x-primary-button>

                        @if (request()->filled('search') || request()->filled('publisher'))
                            <a href="{{ route('admin.books.index') }}"
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
                                    Judul</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Penulis</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Penerbit</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Tahun</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Stok</th>
                                <th scope="col"
                                    class="relative px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($books as $book)
                                <tr class="text-sm text-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ ($books->currentPage() - 1) * $books->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $book->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $book->author }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $book->publisher }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        {{ $book->publication_year }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">{{ $book->stock }}</td>
                                    <td class="relative whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('admin.books.edit', $book->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form method="POST" action="{{ route('admin.books.destroy', $book->id) }}"
                                            class="inline ml-3"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        Data buku tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
