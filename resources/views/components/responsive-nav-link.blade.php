@props(['active'])

@php
$classes = ($active ?? false)
            ? 'mobile-link border-ink/10 bg-white/80 text-sage'
            : 'mobile-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
