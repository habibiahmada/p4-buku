<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <h2 class="section-kicker text-xl">
                    <x-heroicon-o-plus class="h-6 w-6" />
                    Tambah Buku Baru
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.books.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('Judul')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="author" :value="__('Penulis')" />
                            <x-text-input id="author" class="block mt-1 w-full" type="text" name="author"
                                required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="publisher" :value="__('Penerbit')" />
                            <x-text-input id="publisher" class="block mt-1 w-full" type="text" name="publisher"
                                required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="publication_year" :value="__('Tahun Terbit')" />
                            <x-text-input id="publication_year" class="block mt-1 w-full" type="number"
                                name="publication_year" required min="1900" max="{{ date('Y') }}" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="stock" :value="__('Stok')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock"
                                required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                Tambah
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
