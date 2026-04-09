<x-guest-layout>
    <div class="mb-8 space-y-4">
        <h1 class="font-serif text-4xl font-bold leading-tight text-ink sm:text-5xl">
            Daftar
        </h1>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" class="mt-1" type="text" name="name" :value="old('name')" required autofocus
                autocomplete="name" placeholder="Masukkan nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-3" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required
                autocomplete="username" placeholder="nama@sekolah.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-3" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required
                autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-3" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation"
                required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-3" />
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            <a class="inline-flex items-center gap-2 text-sm text-muted transition hover:text-sage"
                href="{{ route('login') }}">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
                Sudah punya akun?
            </a>

            <x-primary-button>
                Daftar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
