<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                value="{{ old('name', $user->name) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                value="{{ old('email', $user->email) }}" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Kata Sandi (Kosongkan jika tidak ingin mengubah)')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select name="role" id="role"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    Admin</option>
                                <option value="siswa" {{ old('role', $user->role) === 'siswa' ? 'selected' : '' }}>
                                    Siswa</option>
                            </select>
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
