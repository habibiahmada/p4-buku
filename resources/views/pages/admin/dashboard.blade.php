<x-app-layout>
    <x-slot name="header">
        {{-- TODO: buat halaman dashboard simple dengan isi statistik  grafik dan lain lain sesuai dengan fitur yang ada di admin yaitu kelola anggota buku dan peminjaman --}}
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between ">
            <div class="space-y-4">
                <h2 class="section-kicker">
                    <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                    Admin Dashboard
                </h2>
            </div>
        </div>
    </x-slot>
</x-app-layout>
