@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'flex items-start gap-3 border border-sage/15 bg-sage-light/80 px-4 py-3 text-sm text-sage']) }}>
        <x-heroicon-o-check-circle class="mt-0.5 h-5 w-5 flex-shrink-0" />
        <span>{{ $status }}</span>
    </div>
@endif
