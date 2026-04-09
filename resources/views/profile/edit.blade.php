<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <span class="section-kicker">
                <x-heroicon-o-user-circle class="h-4 w-4" />
                Profil Akun
            </span>
            <h1 class="section-title">Kelola identitas dan keamanan akun Anda.</h1>
            <p class="section-copy max-w-3xl">
                Semua panel profil di bawah ini sudah diperbarui agar serasi dengan landing page dan seluruh halaman utama aplikasi.
            </p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl px-6 py-10">
        <div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
            <aside class="paper-panel p-7">
                <span class="section-kicker">
                    <x-heroicon-o-identification class="h-4 w-4" />
                    Ringkasan
                </span>
                <h2 class="mt-5 font-serif text-3xl font-bold text-ink">Satu halaman untuk semua pengaturan akun.</h2>
                <p class="mt-4 text-sm leading-7 text-muted">
                    Di sini Anda bisa memperbarui nama dan email, mengganti kata sandi, serta menutup akun jika memang diperlukan.
                </p>

                <ul class="mt-6 space-y-4">
                    <li class="flex items-start gap-3 text-sm text-muted">
                        <x-heroicon-o-check-circle class="mt-0.5 h-5 w-5 flex-shrink-0 text-sage" />
                        Informasi profil ditampilkan dalam panel yang lebih bersih dan mudah dipindai.
                    </li>
                    <li class="flex items-start gap-3 text-sm text-muted">
                        <x-heroicon-o-check-circle class="mt-0.5 h-5 w-5 flex-shrink-0 text-sage" />
                        Form keamanan menggunakan komponen input dan tombol baru yang konsisten.
                    </li>
                    <li class="flex items-start gap-3 text-sm text-muted">
                        <x-heroicon-o-check-circle class="mt-0.5 h-5 w-5 flex-shrink-0 text-sage" />
                        Dialog penghapusan akun kini mengikuti gaya visual aplikasi secara menyeluruh.
                    </li>
                </ul>
            </aside>

            <div class="space-y-6">
                <div class="surface-panel p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="surface-panel p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="surface-panel p-6 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
