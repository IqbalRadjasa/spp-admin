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

<body class="font-sans antialiased bg-gray-100">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md">

            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b">
                <a href="{{ route('dashboard') }}">
                    <h1 class="text-xl font-bold">SPP Admin</h1>
                </a>
            </div>

            <!-- Menu -->
            <nav class="p-4 space-y-2">

                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-200">
                    Dashboard
                </a>

                <a href="{{ route('students.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200">
                    Student Records
                </a>

                <a href="{{ route('bills.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200">
                    List Bills
                </a>

                <a href="{{ route('bills.generate.form') }}" class="block px-4 py-2 rounded hover:bg-gray-200">
                    Generate Bills
                </a>
            </nav>

        </aside>

        <!-- Main Content -->
        <div class="flex-1">

            <!-- Topbar -->
            <header class="bg-white shadow h-16 flex items-center justify-end px-6">

                <div class="flex items-center gap-4">

                    @auth
                        <span>{{ Auth::user()->name }}</span>
                    @endauth

                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button type="submit" class="px-3 py-2 bg-red-500 text-dark bg-gray-200 rounded">
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>

            </header>

            <!-- Page Content -->
            <main class="p-6">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>

</html>
