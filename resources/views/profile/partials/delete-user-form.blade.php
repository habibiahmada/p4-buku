<section class="space-y-6">
    <header class="space-y-3">
        <span class="section-kicker">
            <x-heroicon-o-exclamation-triangle class="h-4 w-4" />
            Zona Sensitif
        </span>
        <h2 class="font-serif text-3xl font-bold text-ink">Hapus akun bila memang diperlukan.</h2>
        <p class="section-copy max-w-2xl">
            Tindakan ini bersifat permanen. Pastikan Anda benar-benar yakin sebelum melanjutkan penghapusan akun.
        </p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        <x-heroicon-o-trash class="h-4 w-4" />
        Hapus Akun
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 items-center justify-center bg-copper-light text-copper">
                    <x-heroicon-o-exclamation-triangle class="h-6 w-6" />
                </div>
                <div class="space-y-3">
                    <h2 class="font-serif text-3xl font-bold text-ink">Konfirmasi penghapusan akun.</h2>
                    <p class="text-sm leading-7 text-muted">
                        Setelah akun dihapus, seluruh data yang terkait akan ikut terhapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi tindakan ini.
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <x-input-label for="password" value="Kata Sandi" />
                <x-text-input id="password" name="password" type="password" class="mt-1" placeholder="Masukkan kata sandi" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-3" />
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    <x-heroicon-o-x-mark class="h-4 w-4" />
                    Batal
                </x-secondary-button>

                <x-danger-button>
                    <x-heroicon-o-trash class="h-4 w-4" />
                    Ya, Hapus Akun
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
