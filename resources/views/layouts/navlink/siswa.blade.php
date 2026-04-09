<x-nav-link :href="route($user->role . '.transactions.index')" :active="request()->routeIs($user->role . '.transactions.*')">
    <x-heroicon-o-document-text class="h-4 w-4" />
    Peminjaman
</x-nav-link>
