<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="description" content="SIKAP - Sistem Integrasi Kinerja, Absensi, dan Perizinan PPPK Paruh Waktu Balai Perakitan dan Pengujian Agroklimat dan Hidrologi Pertanian.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <title>Masuk - {{ config('app.name', 'SIKAP') }} | Layanan Integrasi PPPK</title>

    <!-- Favicon / Logo Tab -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Preload Critical Images for LCP Performance -->
    <link rel="preload" as="image" href="{{ asset('images/brmp_meeting_photo.jpeg') }}" fetchpriority="high">
    <link rel="preload" as="image" href="{{ asset('images/logo.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif !important;
        }
    </style>
</head>
<body class="font-sans text-slate-900 dark:text-slate-100 antialiased selection:bg-green-600 selection:text-white bg-[#F5F5F5] dark:bg-[#121212]">
    <main id="main-content" class="min-h-screen flex flex-col justify-center items-center py-10 px-6 sm:px-10 lg:px-12">
        <div class="w-full max-w-5xl mx-auto my-auto flex flex-col justify-center items-center gap-6">
            <!-- Content Wrapper (Centered, Symmetrical, Equal Height) -->
            <div class="w-full grid grid-cols-1 lg:grid-cols-2 items-stretch gap-10 lg:gap-14">

                <!-- Left Side: Brand presentation -->
                <div class="w-full flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <!-- Brand Header -->
                        <div class="flex items-center gap-3">
                            <!-- Ministry of Agriculture Logo Emblem -->
                            <div class="flex-shrink-0">
                                <x-application-logo class="h-14 w-auto max-w-[56px] object-contain shrink-0" />
                            </div>
                            <!-- Logo Text -->
                            <div class="flex flex-col">
                                <div class="text-2xl font-black text-green-800 dark:text-green-500 tracking-tight leading-none">
                                    SIKAP
                                </div>
                                <span class="text-[9px] font-extrabold text-slate-700 dark:text-slate-300 tracking-wider uppercase mt-1">
                                    Sistem Integrasi Kinerja, Absensi, dan Perizinan PPPK Paruh waktu
                                </span>
                            </div>
                        </div>

                        <!-- Headline text -->
                        <h1 class="text-xl sm:text-2xl lg:text-2xl xl:text-[1.7rem] font-extrabold text-slate-800 dark:text-white leading-snug">
                            Layanan Integrasi Kinerja, Absensi,<br class="hidden sm:inline"> dan Perizinan PPPK Paruh Waktu
                        </h1>
                    </div>

                    <!-- Meeting Image (Flex 1 to fill height and match card bottom) -->
                    <div class="w-full flex-1 min-h-[220px] rounded-[2.5rem] overflow-hidden shadow-lg border border-slate-200/60 dark:border-slate-800">
                        <img
                            src="{{ asset('images/brmp_meeting_photo.jpeg') }}"
                            alt="Rapat Balai"
                            width="542"
                            height="414"
                            fetchpriority="high"
                            decoding="async"
                            class="w-full h-full object-cover"
                            style="object-position: center 20%;"
                        />
                    </div>
                </div>

                <!-- Right Side: Login Card -->
                <div class="w-full flex flex-col justify-center">
                    <!-- Card Container -->
                    <div class="w-full bg-white dark:bg-slate-900 border-t-[6px] border-[#0F5E2B] shadow-2xl overflow-hidden rounded-[2.5rem] p-8 sm:p-10 transition-all duration-300">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Footer Info placed directly under with clean spacing -->
            <footer class="w-full text-center text-[11px] text-slate-600 dark:text-slate-400 font-medium leading-relaxed pt-2">
                Copyright &copy; {{ date('Y') }} | Balai Perakitan dan Pengujian Agroklimat dan Hidrologi Pertanian. All Rights Reserved.
            </footer>
        </div>
    </main>
</body>
</html>