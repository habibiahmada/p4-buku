<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="space-y-4">
                <h2 class="section-kicker text-xl">
                    <x-heroicon-o-user-group class="h-6 w-6" />
                    Kelola Anggota
                </h2>
            </div>
        </div>
    </x-slot>

    @session('success')
        <div class="mb-4 rounded-lg bg-green-50 p-4">
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
                    <dt class="truncate text-sm font-medium text-gray-500">Total Anggota</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalAnggota }}</dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Admin</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalAdmin }}</dd>
                </div>

                <div class="overflow-hidden bg-white px-4 py-5 shadow sm:p-6">
                    <dt class="truncate text-sm font-medium text-gray-500">Total Siswa</dt>
                    <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalSiswa }}</dd>
                </div>
            </div>
        </div>

    </div>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm flex justify-between items-center">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Daftar Anggota</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Kelola informasi anggota perpustakaan di sini.
                    </p>
                </div>
                <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
                    <x-primary-button onclick="window.location='{{ route('admin.users.create') }}'">
                        <x-heroicon-o-plus class="h-5 w-5" />
                        Tambah Anggota
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm">
                <form method="GET" action="{{ route('admin.users.index') }}"
                    class="grid gap-4 p-6 md:grid-cols-[minmax(0,1fr)_220px_auto] md:items-end">
                    <div>
                        <label for="search" class="mb-2 block text-sm font-medium text-gray-700">Cari Anggota</label>
                        <input id="search" name="search" type="text" value="{{ request('search') }}"
                            placeholder="Cari nama atau email"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="role" class="mb-2 block text-sm font-medium text-gray-700">Filter Role</label>
                        <select id="role" name="role"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Semua Role</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            <option value="siswa" @selected(request('role') === 'siswa')>Siswa</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button type="submit" class="justify-center">
                            Filter
                        </x-primary-button>

                        @if (request()->filled('search') || request()->filled('role'))
                            <a href="{{ route('admin.users.index') }}"
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
                                    Nama</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Email</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Role</th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Terdaftar</th>
                                <th scope="col"
                                    class="relative px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($users as $user)
                                <tr class="text-sm text-gray-700">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $user->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-600">
                                        {{ $user->created_at->format('d M Y') }}</td>
                                    <td class="relative whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                            class="inline ml-3"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        Data anggota tidak ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
