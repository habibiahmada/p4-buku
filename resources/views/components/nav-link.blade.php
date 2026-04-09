@props(['active'])

@php
$classes = ($active ?? false)
            ? 'topbar-link topbar-link-active'
            : 'topbar-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
