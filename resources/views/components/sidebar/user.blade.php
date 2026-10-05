<a href="{{ route('dashboard') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path></svg>
    Dashboard
</a>
<a href="{{ route('izin.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('izin.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
    Permohonan Izin
</a>
<a href="{{ route('laporan-mingguan.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('laporan-mingguan.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
    Laporan Kinerja
</a>
<a href="{{ route('absen-wfh.create') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('absen-wfh.*') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
    Absen WFH
</a>
<a href="{{ route('kehadiran.rekap-pribadi') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-[15px] font-medium transition duration-150 {{ request()->routeIs('kehadiran.rekap-pribadi') ? 'bg-white text-gray-700 border-l-4 border-yellow-400 font-semibold pl-3 rounded-l-none' : 'text-gray-100 hover:bg-white/10 hover:text-white pl-4' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 112-2h2a2 2 0 012 2"></path></svg>
    Rekap Kehadiran Saya
</a>