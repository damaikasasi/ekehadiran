<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light">
    <title>{{ config('app.name', 'SIKAP') }} - {{ $title ?? 'Dashboard' }}</title>

    <!-- Favicon / Logo Tab -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    </noscript>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
        :root {
            color-scheme: light !important;
        }
        body, input, select, textarea, button {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;
            color-scheme: light !important;
        }
        /* SweetAlert Red Icon styling for Delete Confirmation */
        .swal2-icon.swal2-warning[style*="rgb(186, 26, 26)"],
        .swal2-icon.swal2-warning[style*="#BA1A1A"],
        .swal2-icon.swal2-warning[style*="#ba1a1a"] {
            border-color: #BA1A1A !important;
            color: #BA1A1A !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-[#f5f5f5]">

<!-- TOP LOADING SHIMMER BAR (Triggers on link navigation) -->
<div id="pageProgressBar" class="fixed top-0 left-0 right-0 h-1 z-[9999] bg-[#0b602b] opacity-0 transition-opacity duration-200 pointer-events-none">
    <div class="h-full w-full bg-gradient-to-r from-emerald-400 via-yellow-400 to-emerald-400 animate-pulse"></div>
</div>

@php
    $portal = match(auth()->user()->role){
        'admin' => 'PORTAL ADMIN',
        'atasan' => 'PORTAL ATASAN',
        'user' => 'PORTAL PEGAWAI',
        default => 'PORTAL'
    };
@endphp

<div
    class="flex min-h-screen bg-[#f5f5f5]"
    x-data="{ 
        mobileSidebarOpen: false,
        desktopSidebarOpen: true
    }"
>

    <!-- 1. MOBILE DRAWER (Only rendered when opened on mobile) -->
    <div x-show="mobileSidebarOpen" class="relative z-50 lg:hidden" x-cloak style="display: none;">
        <!-- Backdrop -->
        <div 
            x-show="mobileSidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileSidebarOpen = false"
            class="fixed inset-0 bg-black/60 backdrop-blur-xs"
        ></div>

        <!-- Mobile Drawer Container -->
        <div class="fixed inset-0 flex z-50 pointer-events-none">
            <div 
                x-show="mobileSidebarOpen"
                x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="relative flex-1 flex flex-col max-w-[280px] w-full bg-[#0b602b] shadow-2xl h-full pointer-events-auto"
                @click.outside="mobileSidebarOpen = false"
            >
                <!-- Mobile Header -->
                <div class="h-20 px-5 flex items-center justify-between border-b border-[#084920]">
                    <div class="flex items-center gap-3 min-w-0">
                        <img src="{{ asset('images/logo.png') }}"
                             alt="Logo BRMP"
                             width="39"
                             height="48"
                             decoding="async"
                             class="h-12 w-auto max-w-[54px] object-contain shrink-0">

                        <div class="min-w-0">
                            <h1 class="text-base font-extrabold text-yellow-400 leading-tight truncate">
                                SIKAP
                            </h1>

                            <span class="block text-[9px] font-black uppercase tracking-[0.18em] text-white/90 truncate">
                                {{ $portal }}
                            </span>
                        </div>
                    </div>

                    <button @click="mobileSidebarOpen = false"
                            type="button"
                            class="p-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition"
                            aria-label="Tutup Menu Sidebar"
                            title="Tutup Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                    @include('components.sidebar.' . auth()->user()->role)
                </nav>

                <!-- Logout -->
                <div class="p-4 border-t border-white">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-white hover:bg-white/20 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. DESKTOP SIDEBAR (Only visible on lg screens) -->
    <aside
        class="hidden lg:flex bg-[#0b602b] flex-col h-screen sticky top-0 self-start shadow-sm transition-all duration-300 z-20 shrink-0 border-r border-[#084920]"
        :class="desktopSidebarOpen ? 'w-64' : 'w-0 border-none overflow-hidden opacity-0 pointer-events-none'"
    >
        <!-- Logo Header -->
        <div class="h-20 px-5 flex items-center border-b border-[#084920] min-w-[256px]">
            <div class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Logo BRMP"
                     width="39"
                     height="48"
                     decoding="async"
                     class="h-12 w-auto max-w-[54px] object-contain shrink-0">

                <div class="min-w-0">
                    <h1 class="text-base font-extrabold text-yellow-400 leading-tight truncate">
                        SIKAP
                    </h1>

                    <span class="block text-[9px] font-black uppercase tracking-[0.18em] text-white/90 truncate">
                        {{ $portal }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto min-w-[256px]">
            @include('components.sidebar.' . auth()->user()->role)
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-white min-w-[256px]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-white hover:bg-white/20 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col min-h-screen min-w-0">

        <header class="h-16 sm:h-20 flex items-center justify-between px-4 sm:px-6 lg:px-8 bg-white border-b border-gray-200">

            <!-- TOGGLE SIDEBAR (HAMBURGER BUTTON) -->
            <button @click="if (window.innerWidth >= 1024) { desktopSidebarOpen = !desktopSidebarOpen; } else { mobileSidebarOpen = true; }"
                    type="button"
                    class="p-2 -ml-2 text-gray-700 hover:text-[#0e622b] hover:bg-gray-100 rounded-xl transition focus:outline-none cursor-pointer"
                    title="Toggle Sidebar"
                    aria-label="Toggle Sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="flex items-center gap-4 sm:gap-5">

                <!-- Bell -->
                @if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'atasan')
                <a href="{{ route('pengumuman.index') }}" class="relative p-1 focus:outline-none block" aria-label="Lihat Pengumuman" title="Pengumuman">
                    <svg class="w-5 h-5 text-gray-700 hover:text-[#0e622b] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </a>
                @endif

                <!-- Tanggal Terkini (Format: tanggal bulan tahun) -->
                <div class="flex items-center gap-2 text-xs sm:text-sm font-semibold text-white bg-[#0b602b] px-3.5 py-1.5 rounded-xl shadow-xs border border-[#084d22]">
                    <svg class="w-4 h-4 text-emerald-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
                </div>

                <!-- User Profile Pill & Dropdown -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    @php
                        $pegawai = auth()->user()->pegawai;
                        $displayName = $pegawai && $pegawai->nama ? $pegawai->nama : auth()->user()->name;
                    @endphp

                    <button @click="userMenuOpen = !userMenuOpen"
                            type="button"
                            aria-label="Menu Profil {{ $displayName }}"
                            title="Menu Profil"
                            class="flex items-center justify-center rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-[#0b602b] focus:ring-offset-2 cursor-pointer group p-0.5">

                        @if($pegawai && $pegawai->foto && Storage::disk('public')->exists($pegawai->foto))
                            <img src="{{ asset('storage/'.$pegawai->foto) }}"
                                 alt="{{ $displayName }}"
                                 width="36"
                                 height="36"
                                 loading="lazy"
                                 decoding="async"
                                 class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border border-[#dfead8] shadow-xs group-hover:ring-2 group-hover:ring-[#0b602b]/40 transition">
                        @else
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[#0b602b] text-white flex items-center justify-center font-bold text-xs shadow-xs group-hover:bg-[#084d22] transition">
                                {{ strtoupper(substr($displayName, 0, 2)) }}
                            </div>
                        @endif
                    </button>

                    <!-- Floating Dropdown Menu -->
                    <div x-show="userMenuOpen"
                         @click.outside="userMenuOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 divide-y divide-gray-100"
                         style="display: none;">

                        <div class="px-4 py-3">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ $displayName }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                                {{ auth()->user()->role }}
                            </span>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-[#0b602b] transition">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Profil Saya
                            </a>
                        </div>

                        <div class="py-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition text-left cursor-pointer">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </header>

        <main class="flex-1 px-4 sm:px-6 lg:px-8 pb-8 pt-4 sm:pt-6 min-w-0">
            {{ $slot }}
        </main>

    </div>

</div>

<script type="module">
document.addEventListener('DOMContentLoaded', () => {

    const progressBar = document.getElementById('pageProgressBar');
    if (progressBar) {
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"])');
            if (link && !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                progressBar.style.opacity = '1';
            }
        }, { passive: true });
    }

    document.querySelectorAll('.confirm-form').forEach(form => {
        form.addEventListener('submit', function(e){
            e.preventDefault();

            const isDelete = (this.querySelector('input[name="_method"][value="DELETE"]') !== null) 
                          || (this.querySelector('input[name="_method"][value="delete"]') !== null)
                          || (this.dataset.title && this.dataset.title.toLowerCase().includes('hapus'))
                          || (this.dataset.text && this.dataset.text.toLowerCase().includes('hapus'))
                          || (this.dataset.confirmText && this.dataset.confirmText.toLowerCase().includes('hapus'))
                          || (this.dataset.confirmColor && (this.dataset.confirmColor.toLowerCase() === '#ba1a1a' || this.dataset.confirmColor.toLowerCase() === '#dc2626'));

            const icon = this.dataset.icon || 'warning';
            const iconColor = this.dataset.iconColor || (isDelete ? '#BA1A1A' : (this.dataset.icon === 'question' ? '#0b602b' : undefined));
            const confirmColor = this.dataset.confirmColor || (isDelete ? '#BA1A1A' : '#0b602b');
            const cancelColor = this.dataset.cancelColor || '#6b7280';
            const confirmText = this.dataset.confirmText || (isDelete ? 'Ya, Hapus' : 'Ya');
            const cancelText = this.dataset.cancelText || 'Batal';

            Swal.fire({
                title: this.dataset.title || 'Konfirmasi',
                text: this.dataset.text || 'Apakah Anda yakin?',
                icon: icon,
                iconColor: iconColor,
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: cancelColor,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true
            }).then((result)=>{
                if(result.isConfirmed){
                    form.submit();
                }
            });
        });
    });

});
</script>

</body>
</html>