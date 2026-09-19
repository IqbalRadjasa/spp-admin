<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-white">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12">

        <!-- Left Side: Hero Branding Panel (Hidden on Mobile) -->
        <div
            class="hidden lg:flex lg:col-span-5 xl:col-span-6 bg-[#6E3511] text-white flex-col justify-between p-12 relative overflow-hidden">
            <!-- Background Decorative Overlay with Soft Radial Accent -->
            <div class="absolute inset-0 bg-gradient-to-br from-[#597928]/40 via-[#6E3511] to-[#4A230B] z-0"></div>
            <div
                class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full bg-[#91AC67]/20 blur-3xl z-0 pointer-events-none">
            </div>

            <!-- Top Logo / App Name -->
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-[#597928] text-[#FCECD8] flex items-center justify-center font-bold text-lg shadow-lg shadow-[#4A230B]/50 ring-2 ring-[#91AC67]/30">
                        S
                    </div>
                    <span class="text-xl font-bold tracking-wider uppercase text-[#FCECD8]">SkolaPayd</span>
                </div>
            </div>

            <!-- Hero Content -->
            <div class="relative z-10 max-w-md">
                <h1 class="text-3xl font-extrabold tracking-tight text-[#FCECD8] sm:text-4xl leading-tight">
                    Kelola Pembayaran SPP Sekolah Lebih Praktis.
                </h1>
                <p class="mt-4 text-[#FCECD8]/80 text-sm leading-relaxed">
                    Sistem informasi manajemen SPP terintegrasi untuk siswa, orang tua, dan bendahara sekolah secara
                    real-time.
                </p>
            </div>

            <!-- Footer Copyright -->
            <div class="relative z-10 text-xs text-[#91AC67]">
                &copy; {{ date('Y') }} Portal Sekolah. All rights reserved.
            </div>
        </div>

        <!-- Right Side: Auth Form Container -->
        <div class="flex items-center justify-center p-6 sm:p-12 lg:col-span-7 xl:col-span-6 bg-gray-50/50">
            <div
                class="w-full max-w-md space-y-6 bg-white p-8 sm:p-10 rounded-2xl shadow-xl shadow-gray-100 border border-gray-100">
                {{ $slot }}
            </div>
        </div>

    </div>
</body>

</html>
