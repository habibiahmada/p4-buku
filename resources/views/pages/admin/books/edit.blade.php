<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <h2 class="section-kicker text-xl">
                    <x-heroicon-o-pencil class="h-6 w-6" />
                    {{ __('Edit Buku') }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.books.update', $book->id) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" :value="__('Judul')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                value="{{ old('title', $book->title) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="author" :value="__('Penulis')" />
                            <x-text-input id="author" class="block mt-1 w-full" type="text" name="author"
                                value="{{ old('author', $book->author) }}" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="publisher" :value="__('Penerbit')" />
                            <x-text-input id="publisher" class="block mt-1 w-full" type="text" name="publisher"
                                value="{{ old('publisher', $book->publisher) }}" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="publication_year" :value="__('Tahun Terbit')" />
                            <x-text-input id="publication_year" class="block mt-1 w-full" type="number"
                                name="publication_year" value="{{ old('publication_year', $book->publication_year) }}"
                                required min="1900" max="{{ date('Y') }}" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="stock" :value="__('Stok')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock"
                                value="{{ old('stock', $book->stock) }}" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
