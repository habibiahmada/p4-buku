<x-guest-layout>
    <div class="mb-8 space-y-4">
        <h1 class="font-serif text-4xl font-bold leading-tight text-ink sm:text-5xl">
            Verifikasi Email
        </h1>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 flex items-start gap-3 border border-sage/15 bg-sage-light/80 px-4 py-3 text-sm text-sage">
            <x-heroicon-o-check-circle class="mt-0.5 h-5 w-5 flex-shrink-0" />
            <span>Tautan verifikasi baru sudah dikirim ke email Anda.</span>
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <x-primary-button>
                Kirim Ulang Verifikasi
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn-secondary">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
