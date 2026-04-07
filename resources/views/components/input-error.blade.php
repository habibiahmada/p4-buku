@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'space-y-2 text-sm text-copper']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-start gap-2">
                <x-heroicon-o-exclamation-circle class="mt-0.5 h-4 w-4 flex-shrink-0" />
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
