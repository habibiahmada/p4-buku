<x-nav-link :href="route( $user->role . '.users.index')" :active="request()->routeIs( $user->role . '.users.*')">
    <x-heroicon-o-users class="h-4 w-4" />
    Anggota
</x-nav-link>

<x-nav-link :href="route( $user->role . '.books.index')" :active="request()->routeIs( $user->role . '.books.*')">
    <x-heroicon-o-book-open class="h-4 w-4" />
    Buku
</x-nav-link>

<x-nav-link :href="route( $user->role . '.transactions.index')" :active="request()->routeIs( $user->role . '.transactions.*')">
    <x-heroicon-o-document-text class="h-4 w-4" />
    Peminjaman
</x-nav-link>
