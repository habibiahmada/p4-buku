<x-guest-layout>
    <div class="mb-8 space-y-4">
        <h1 class="font-serif text-4xl font-bold leading-tight text-ink sm:text-5xl">
            Reset Kata Sandi
        </h1>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email', $request->email)" required
                autofocus autocomplete="username" placeholder="nama@sekolah.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-3" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi Baru" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required
                autocomplete="new-password" placeholder="Masukkan kata sandi baru" />
            <x-input-error :messages="$errors->get('password')" class="mt-3" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" class="mt-1" type="password" name="password_confirmation"
                required autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-3" />
        </div>

        <div class="flex justify-end pt-2">
            <x-primary-button>
                Simpan Kata Sandi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
