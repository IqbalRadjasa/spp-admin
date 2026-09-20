<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPP-Admin') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-[#FBF6EF]" data-success="{{ session('success') }}" data-error="{{ session('error') }}">
    <div x-data="{ sidebarOpen: true, mobileSidebarOpen: false }" class="flex h-screen">

        @include('layouts.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            @include('layouts.header')

            <main class="min-w-0 flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>

</html>
