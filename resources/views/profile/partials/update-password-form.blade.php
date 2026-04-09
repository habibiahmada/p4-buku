<section>
    <header class="space-y-3">
        <span class="section-kicker">
            <x-heroicon-o-lock-closed class="h-4 w-4" />
            Keamanan
        </span>
        <h2 class="font-serif text-3xl font-bold text-ink">Ganti kata sandi akun.</h2>
        <p class="section-copy max-w-2xl">
            Gunakan kata sandi yang panjang dan unik untuk menjaga akses ke dashboard tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-8 space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" value="Kata Sandi Saat Ini" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-3" />
        </div>

        <div>
            <x-input-label for="update_password_password" value="Kata Sandi Baru" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-3" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" value="Konfirmasi Kata Sandi Baru" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-3" />
        </div>

        <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center">
            <x-primary-button>
                <x-heroicon-o-shield-check class="h-4 w-4" />
                Perbarui Kata Sandi
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="flex items-center gap-2 text-sm text-sage">
                    <x-heroicon-o-check-circle class="h-4 w-4" />
                    Kata sandi berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
