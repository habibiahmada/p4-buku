<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <h2 class="section-kicker text-xl">
                    <x-heroicon-o-squares-2x2 class="h-6 w-6" />
                    Lihat Peminjaman
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Detail Peminjaman</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Informasi lengkap transaksi peminjaman ini.
                    </p>
                </div>

                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">ID Peminjaman</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $borrow->id }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Nama Peminjam</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $borrow->user->name }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $borrow->borrowed_date ? \Carbon\Carbon::parse($borrow->borrowed_date)->format('d M Y') : '-' }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Kembali</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $borrow->due_date ? \Carbon\Carbon::parse($borrow->due_date)->format('d M Y') : '-' }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($borrow->status === 'borrowed') bg-yellow-100 text-yellow-800
                                    @elseif($borrow->status === 'returned') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($borrow->status) }}
                                </span>
                            </dd>
                        </div>
                        @if($borrow->returned_date)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Tanggal Dikembalikan</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ \Carbon\Carbon::parse($borrow->returned_date)->format('d M Y') }}</dd>
                        </div>
                        @endif
                        @if($borrow->charge)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Denda</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">Rp {{ number_format($borrow->charge, 0, ',', '.') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Daftar Buku Dipinjam</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Buku-buku yang dipinjam dalam transaksi ini.
                    </p>
                </div>

                <div class="border-t border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($borrow->borrowDetails as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->book->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->qty }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Tidak ada buku</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>