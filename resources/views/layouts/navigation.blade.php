@php
    $user = Auth::user();
    $initials = collect(explode(' ', $user->name))
        ->filter()
        ->take(2)
        ->map(fn ($segment) => strtoupper(substr($segment, 0, 1)))
        ->implode('');
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-ink/10 bg-linen/90 backdrop-blur-sm">
    <div class="mx-auto max-w-6xl px-6">
        <div class="flex h-20 items-center justify-between gap-6">
            <div class="flex items-center gap-10">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-4">
                    <x-application-logo class="h-11 w-11" />
                    <div class="hidden sm:block">
                        <p class="font-serif text-xl font-semibold text-ink">{{ config('app.name', 'Aksara Pustaka') }}</p>
                        <p class="font-mono text-[10px] uppercase tracking-[0.28em] text-muted">Ruang kerja perpustakaan</p>
                    </div>
                </a>

                <div class="hidden items-center gap-2 md:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <x-heroicon-o-squares-2x2 class="h-4 w-4" />
                        Dashboard
                    </x-nav-link>
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                        <x-heroicon-o-user-circle class="h-4 w-4" />
                        Profil
                    </x-nav-link>
                    <a href="{{ route('home') }}" class="topbar-link">
                        <x-heroicon-o-home class="h-4 w-4" />
                        Landing
                    </a>
                </div>
            </div>

            <div class="hidden items-center gap-4 sm:flex">
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="paper-panel inline-flex items-center gap-3 px-3 py-2.5 text-left focus:outline-none focus:ring-2 focus:ring-sage/30">
                            <span class="flex h-10 w-10 items-center justify-center bg-sage text-[11px] font-mono uppercase tracking-[0.18em] text-linen">
                                {{ $initials }}
                            </span>
                            <span class="hidden min-w-0 sm:block">
                                <span class="block truncate text-sm font-medium text-ink">{{ $user->name }}</span>
                                <span class="block truncate text-xs text-muted">{{ $user->email }}</span>
                            </span>
                            <x-heroicon-o-chevron-down class="h-4 w-4 text-muted" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            <x-heroicon-o-user-circle class="h-5 w-5 text-sage" />
                            Profil Saya
                        </x-dropdown-link>

                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-ink transition duration-150 hover:bg-white">
                            <x-heroicon-o-home class="h-5 w-5 text-sage" />
                            Lihat landing page
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <x-heroicon-o-arrow-right-start-on-rectangle class="h-5 w-5 text-copper" />
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center md:hidden">
                <button @click="open = ! open" class="inline-flex h-11 w-11 items-center justify-center border border-ink/10 bg-white/70 text-ink transition hover:border-sage hover:text-sage focus:outline-none focus:ring-2 focus:ring-sage/30">
                    <x-heroicon-o-bars-3 x-show="! open" class="h-5 w-5" />
                    <x-heroicon-o-x-mark x-show="open" x-cloak class="h-5 w-5" />
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-cloak class="border-t border-ink/10 bg-linen/95 px-6 py-5 md:hidden">
        <div class="space-y-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <x-heroicon-o-squares-2x2 class="h-5 w-5" />
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')">
                <x-heroicon-o-user-circle class="h-5 w-5" />
                Profil
            </x-responsive-nav-link>
            <a href="{{ route('home') }}" class="mobile-link">
                <x-heroicon-o-home class="h-5 w-5 text-sage" />
                Landing page
            </a>
        </div>

        <div class="mt-5 border border-ink/10 bg-white/70 p-4">
            <div class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center bg-sage text-[11px] font-mono uppercase tracking-[0.18em] text-linen">
                    {{ $initials }}
                </span>
                <div class="min-w-0">
                    <div class="truncate text-sm font-medium text-ink">{{ $user->name }}</div>
                    <div class="truncate text-xs text-muted">{{ $user->email }}</div>
                </div>
            </div>

            <div class="mt-4 space-y-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <x-heroicon-o-arrow-right-start-on-rectangle class="h-5 w-5" />
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
