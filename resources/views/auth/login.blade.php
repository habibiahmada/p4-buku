<x-guest-layout>
    <div class="mb-8 space-y-4">
        <h1 class="font-serif text-4xl font-bold leading-tight text-ink sm:text-5xl">
            Masuk
        </h1>
    </div>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autofocus
                autocomplete="username" placeholder="nama@sekolah.id" />
            <x-input-error :messages="$errors->get('email')" class="mt-3" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" class="mt-1" type="password" name="password" required
                autocomplete="current-password" placeholder="Masukkan kata sandi" />
            <x-input-error :messages="$errors->get('password')" class="mt-3" />
        </div>

        <label for="remember_me" class="flex items-center gap-3 border border-ink/10 bg-white/70 px-4 py-3 text-sm text-muted">
            <input id="remember_me" type="checkbox" class="border-ink/20 text-sage focus:ring-sage/30" name="remember">
            <span>Ingat sesi saya di perangkat ini</span>
        </label>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
            @if (Route::has('password.request'))
                <a class="inline-flex items-center gap-2 text-sm text-muted transition hover:text-sage" href="{{ route('password.request') }}">
                    <x-heroicon-o-key class="h-4 w-4" />
                    Lupa kata sandi?
                </a>
            @endif

            <x-primary-button>
                <x-heroicon-o-arrow-right-end-on-rectangle class="h-4 w-4" />
                Masuk
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
