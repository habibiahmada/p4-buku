<section>
    <header class="space-y-3">
        <span class="section-kicker">
            <x-heroicon-o-identification class="h-4 w-4" />
            Informasi Profil
        </span>
        <h2 class="font-serif text-3xl font-bold text-ink">Perbarui nama dan alamat email.</h2>
        <p class="section-copy max-w-2xl">
            Pastikan identitas akun selalu akurat agar komunikasi dan pengelolaan akses berjalan lancar.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-8 space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name', $user->name)" required autofocus
                autocomplete="name" />
            <x-input-error class="mt-3" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1" :value="old('email', $user->email)" required
                autocomplete="username" />
            <x-input-error class="mt-3" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 border border-copper/15 bg-copper-light/70 p-4">
                    <p class="text-sm leading-7 text-muted">
                        Alamat email Anda belum terverifikasi.
                        <button form="send-verification" class="font-medium text-copper transition hover:text-[#9c561a]">
                            Kirim ulang email verifikasi
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 flex items-start gap-2 text-sm text-sage">
                            <x-heroicon-o-check-circle class="mt-0.5 h-4 w-4 flex-shrink-0" />
                            <span>Tautan verifikasi baru sudah dikirim ke alamat email Anda.</span>
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">
            <x-primary-button>
                <x-heroicon-o-check class="h-4 w-4" />
                Simpan Perubahan
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="flex items-center gap-2 text-sm text-sage">
                    <x-heroicon-o-check-circle class="h-4 w-4" />
                    Profil berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
