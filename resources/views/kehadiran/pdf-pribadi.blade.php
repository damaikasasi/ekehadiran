<!DOCTYPE html>
<html>
<head>
    <title>Rekap Kehadiran - {{ $pegawai->nama }}</title>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
        }
        .info {
            margin-bottom: 20px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 3px 0;
            font-size: 11px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            border: 1px solid #ccc;
            padding: 8px;
            background-color: #f9f9f9;
        }
        .summary-item .label {
            font-size: 9px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 4px;
        }
        .summary-item .value {
            font-size: 14px;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ccc;
            padding: 6px 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;
        }
        .data-table th {
            background-color: #f0f0f0;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            color: #1f2937;
        }
        .text-center { text-align: center; }
        .text-green { color: #059669; }
        .text-red { color: #dc2626; }
        .text-orange { color: #ea580c; }
        .text-gray-500 { color: #6b7280; }
        .font-bold { font-weight: bold; }
        .font-semibold { font-weight: bold; }
        .badge {
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-hadir { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .badge-terlambat { background-color: #fff7ed; color: #c2410c; border: 1px solid #fed7aa; }
        .badge-alpha { background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-izin { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .badge-libur { background-color: #f3f4f6; color: #4b5563; border: 1px solid #e5e7eb; }
        .foto-thumbnail {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        .lampiran-section {
            page-break-before: always;
            margin-top: 20px;
        }
        .lampiran-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        .lampiran-header h3 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .lampiran-header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }
        .foto-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .foto-grid td {
            width: 50%;
            padding: 10px;
            vertical-align: top;
            text-align: center;
        }
        .foto-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #fafafa;
        }
        .foto-card img {
            width: 100%;
            max-width: 220px;
            height: auto;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .foto-card .caption {
            margin-top: 8px;
            font-size: 11px;
            color: #555;
        }
        .foto-card .caption strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>REKAP KEHADIRAN PEGAWAI</h2>
        <p>Bulan: {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="15%"><strong>Nama</strong></td>
                <td width="2%">:</td>
                <td width="33%">{{ $pegawai->nama }}</td>
                <td width="15%"><strong>Divisi</strong></td>
                <td width="2%">:</td>
                <td width="33%">{{ $pegawai->divisi->nama_divisi ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>NIP</strong></td>
                <td>:</td>
                <td>{{ $pegawai->user->nip ?? '-' }}</td>
                <td><strong>Jabatan</strong></td>
                <td>:</td>
                <td>{{ $pegawai->jabatan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Hadir</div>
            <div class="value text-green">{{ $ringkasan['hadir'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Terlambat</div>
            <div class="value text-orange">
                {{ $ringkasan['terlambat'] }}
                @if(($ringkasan['menit_telat'] ?? 0) > 0)
                    <span style="font-size: 8px; font-weight: normal; color: #dc2626;">({{ $ringkasan['menit_telat'] }} min)</span>
                @endif
            </div>
        </div>
        <div class="summary-item">
            <div class="label">Izin / Cuti</div>
            <div class="value" style="color: #1d4ed8;">{{ $ringkasan['izin'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Alpha</div>
            <div class="value text-red">{{ $ringkasan['alpha'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">WFO</div>
            <div class="value">{{ $ringkasan['wfo'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">WFH</div>
            <div class="value">{{ $ringkasan['wfh'] }}</div>
        </div>
        @if(($ringkasan['total_upacara'] ?? 0) > 0)
        <div class="summary-item">
            <div class="label">Upacara</div>
            <div class="value" style="color: #854d0e;">{{ $ringkasan['upacara_hadir'] }}/{{ $ringkasan['total_upacara'] }}</div>
        </div>
        @endif
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="18%">Tanggal</th>
                <th width="17%">Jam Masuk</th>
                <th width="17%">Jam Keluar</th>
                <th width="16%">Upacara</th>
                <th width="18%">Foto WFH</th>
                <th width="9%">Jenis</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
                @php
                    $fotoItem = null;
                    if ($item->jenis === 'wfh' && $item->foto && isset($fotoWfh)) {
                        $fotoItem = $fotoWfh->first(function ($f) use ($item) {
                            return $f->tanggal === $item->tanggal && $f->jam_masuk === $item->jam_masuk;
                        });
                    }
                @endphp
                <tr style="{{ $item->is_libur ? 'background-color: #fafafa;' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</td>
                    
                    {{-- Jam Masuk --}}
                    <td class="text-center {{ $item->is_libur ? 'text-gray-500 font-bold' : ($item->is_izin ? 'text-orange font-bold' : '') }}">
                        @if($item->is_libur || $item->is_izin)
                            {{ $item->jam_masuk }}
                        @elseif($item->is_kehadiran && $item->jam_masuk)
                            <div class="{{ ($item->menit_telat > 0 || $item->status === 'terlambat') ? 'text-red font-bold' : 'text-green font-bold' }}">
                                {{ $item->jam_masuk }}
                            </div>
                            @if($item->menit_telat > 0 || $item->status === 'terlambat')
                                <div style="font-size: 8px; color: #dc2626; font-weight: bold; margin-top: 2px;">
                                    (terlambat {{ (int) round($item->menit_telat) }} menit)
                                </div>
                            @endif
                        @elseif($item->status === 'alpha')
                            <span class="text-red font-bold">Alpha / Tidak Hadir</span>
                        @else
                            -
                        @endif
                    </td>

                    {{-- Jam Keluar --}}
                    <td class="text-center {{ $item->is_libur ? 'text-gray-500 font-bold' : ($item->is_izin ? 'text-orange font-bold' : '') }}">
                        @if($item->is_libur || $item->is_izin)
                            {{ $item->jam_keluar }}
                        @elseif($item->is_kehadiran && $item->jam_keluar)
                            <div class="{{ $item->menit_pulang_awal > 0 ? 'text-red font-bold' : 'text-green font-bold' }}">
                                {{ $item->jam_keluar }}
                            </div>
                            @if($item->menit_pulang_awal > 0)
                                <div style="font-size: 8px; color: #dc2626; font-weight: bold; margin-top: 2px;">
                                    (pulang lebih awal {{ (int) round($item->menit_pulang_awal) }} menit)
                                </div>
                            @endif
                        @elseif($item->status === 'alpha')
                            <span style="color: #999;">-</span>
                        @else
                            {{ $item->jam_keluar ?? '-' }}
                        @endif
                    </td>

                    {{-- Upacara --}}
                    <td class="text-center" style="vertical-align: middle;">
                        @if($item->upacara)
                            @if($item->upacara->status === 'hadir')
                                <span style="color: #047857; font-weight: bold;">Hadir</span>
                            @elseif($item->upacara->status === 'izin')
                                <span style="color: #1d4ed8; font-weight: bold;">Izin</span>
                            @else
                                <span style="color: #dc2626; font-weight: bold;">Tidak Hadir</span>
                            @endif
                            <div style="font-size: 7.5px; color: #555555; margin-top: 2px; line-height: 1.1;">
                                {{ $item->upacara->nama_upacara }}
                            </div>
                        @else
                            -
                        @endif
                    </td>

                    {{-- Foto WFH --}}
                    <td class="text-center" style="vertical-align: middle; padding: 5px 2px;">
                        @if ($fotoItem && ($fotoItem->foto_base64 || $fotoItem->foto_keluar_base64))
                            <table style="width: auto; margin: 0 auto; border-collapse: collapse; border: none;">
                                <tr>
                                    @if ($fotoItem->foto_base64)
                                        <td style="border: none; padding: 0 4px; text-align: center; vertical-align: middle;">
                                            <img src="{{ $fotoItem->foto_base64 }}" alt="Foto Masuk" style="width: 44px; height: 44px; border: 1.5px solid #059669; border-radius: 4px; display: block; margin: 0 auto;">
                                            <div style="font-size: 7.5px; font-weight: bold; color: #ffffff; background-color: #059669; border-radius: 2px; margin-top: 2px; padding: 1px 3px; display: block;">IN</div>
                                        </td>
                                    @endif
                                    @if ($fotoItem->foto_keluar_base64)
                                        <td style="border: none; padding: 0 4px; text-align: center; vertical-align: middle;">
                                            <img src="{{ $fotoItem->foto_keluar_base64 }}" alt="Foto Pulang" style="width: 44px; height: 44px; border: 1.5px solid #dc2626; border-radius: 4px; display: block; margin: 0 auto;">
                                            <div style="font-size: 7.5px; font-weight: bold; color: #ffffff; background-color: #dc2626; border-radius: 2px; margin-top: 2px; padding: 1px 3px; display: block;">OUT</div>
                                        </td>
                                    @endif
                                </tr>
                            </table>
                        @else
                            -
                        @endif
                    </td>

                    {{-- Jenis --}}
                    <td class="text-center font-bold">
                        {{ strtoupper($item->jenis) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Belum ada data kehadiran bulan ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>

