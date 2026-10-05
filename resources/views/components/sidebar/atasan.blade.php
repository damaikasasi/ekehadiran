<a href="{{ route('dashboard') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
    Beranda
</a>
<a href="{{ route('laporan-mingguan.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('laporan-mingguan.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
    Nilai Laporan
</a>
<a href="{{ route('izin.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('izin.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    Verifikasi Izin
</a>
<a href="{{ route('pegawai.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('pegawai.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
    Daftar Pegawai
</a>