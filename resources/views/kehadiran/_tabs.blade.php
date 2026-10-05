<div class="flex gap-6 border-b border-gray-200 mb-6 overflow-x-auto whitespace-nowrap scrollbar-hide pb-2">
    <a href="{{ route('kehadiran.create') }}"
       class="pb-3 px-1 text-sm font-medium border-b-2 {{ request()->routeIs('kehadiran.create') ? 'border-[#0b602b] text-[#0b602b] font-bold' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
        Unggah Fingerprint
    </a>
    <a href="{{ route('kehadiran.index') }}"
       class="pb-3 px-1 text-sm font-medium border-b-2 {{ request()->routeIs('kehadiran.index') ? 'border-[#0b602b] text-[#0b602b] font-bold' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
        Data Kehadiran
    </a>
    <a href="{{ route('kehadiran.wfh') }}"
       class="pb-3 px-1 text-sm font-medium border-b-2 {{ request()->routeIs('kehadiran.wfh') ? 'border-[#0b602b] text-[#0b602b] font-bold' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
        Presensi WFH
    </a>
    <a href="{{ route('upacara.index') }}"
       class="pb-3 px-1 text-sm font-medium border-b-2 {{ request()->routeIs('upacara.*') ? 'border-[#0b602b] text-[#0b602b] font-bold' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
        Presensi Upacara
    </a>
    <a href="{{ route('kehadiran.rekap') }}"
       class="pb-3 px-1 text-sm font-medium border-b-2 {{ request()->routeIs('kehadiran.rekap') ? 'border-[#0b602b] text-[#0b602b] font-bold' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
        Rekapitulasi
    </a>
    <a href="{{ route('kehadiran.rekap-pegawai') }}"
       class="pb-3 px-1 text-sm font-medium border-b-2 {{ request()->routeIs('kehadiran.rekap-pegawai*') ? 'border-[#0b602b] text-[#0b602b] font-bold' : 'border-transparent text-gray-600 hover:text-gray-900' }}">
        Rekap Pegawai
    </a>
</div>