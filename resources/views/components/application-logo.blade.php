@php
    $initials = collect(explode(' ', config('app.name', 'Aksara Pustaka')))
        ->filter()
        ->take(2)
        ->map(fn ($segment) => strtoupper(substr($segment, 0, 1)))
        ->implode('');
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center justify-center border border-sage/25 bg-sage text-linen shadow-[0_20px_40px_-24px_rgba(74,124,89,0.9)]']) }}>
    <span class="ps-[0.28rem] font-mono text-[11px] font-medium tracking-[0.28em]">{{ $initials }}</span>
</span>
