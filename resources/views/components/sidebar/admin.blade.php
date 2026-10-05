<div class="flex flex-col gap-1">
    <!-- Beranda -->
    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
        Beranda
    </a>

    <!-- Pegawai -->
    <a href="{{ route('pegawai.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('pegawai.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        Pegawai
    </a>

    <!-- Divisi -->
    <a href="{{ route('divisi.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('divisi.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        Divisi
    </a>

    <!-- Kehadiran -->
    <a href="{{ route('kehadiran.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs(['kehadiran.*', 'upacara.*']) ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
        Kehadiran
    </a>

    <!-- Permohonan Izin -->
    <a href="{{ route('izin.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('izin.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        Permohonan Izin
    </a>

    <!-- Laporan Mingguan -->
    <a href="{{ route('laporan-mingguan.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('laporan-mingguan.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
        Laporan Bulanan
    </a>

    <!-- Pengumuman -->
    <a href="{{ route('pengumuman.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('pengumuman.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
        Pengumuman
    </a>
</div>