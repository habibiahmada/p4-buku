<x-guest-layout>
    <div class="mb-8 space-y-4">
        <span class="section-kicker">
            <x-heroicon-o-envelope class="h-4 w-4" />
            Reset Akses
        </span>
        <h1 class="font-serif text-4xl font-bold leading-tight text-ink sm:text-5xl">
            Minta tautan untuk mengatur ulang kata sandi.
        </h1>
        <p class="section-copy max-w-2xl">
            Masukkan email akun Anda. Kami akan mengirimkan tautan pemulihan agar Anda bisa kembali masuk dengan aman.
        </p>
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
                <x-heroicon-o-paper-airplane class="h-4 w-4" />
                Kirim Tautan Reset
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
