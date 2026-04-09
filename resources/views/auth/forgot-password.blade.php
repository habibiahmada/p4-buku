<x-guest-layout>
    <div class="mb-8 space-y-4">
        <h1 class="font-serif text-4xl font-bold leading-tight text-ink sm:text-5xl">
            Lupa Kata Sandi
        </h1>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autofocus
                placeholder="nama@sekolah.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-3" />
        </div>

        <div class="flex justify-end pt-2">
            <x-primary-button>
                Kirim Tautan Reset
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
