<x-guest-layout>
    <div class="mb-8 space-y-4">
        <h1 class="font-serif text-4xl font-bold leading-tight text-ink sm:text-5xl">
            Konfirmasi Kata Sandi
        </h1>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required
                autocomplete="current-password" placeholder="Masukkan kata sandi Anda" />
            <x-input-error :messages="$errors->get('password')" class="mt-3" />
        </div>

        <div class="flex justify-end pt-2">
            <x-primary-button>
                Konfirmasi
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
