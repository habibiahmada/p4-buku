<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Aksara Pustaka') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('layouts.navigation')

    <div class="relative min-h-screen overflow-hidden">
        <div
            class="pointer-events-none absolute inset-x-0 top-0 h-80 bg-[radial-gradient(circle_at_top,_rgba(74,124,89,0.18),_transparent_58%)]">
        </div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-72 w-72 bg-copper/10 blur-3xl"></div>

        @isset($header)
            <header class="relative border-b border-ink/10">
                <div class="mx-auto max-w-7xl px-6 py-10 sm:py-12">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="relative">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
