<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja Triwulan - {{ $pegawai->nama }}</title>

    <style>
        @page {
            margin: 12mm 20mm 12mm 20mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
            font-size: 11.5px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* =====================================================
           COVER STYLES
        ===================================================== */

        .cover-table {
            width: 100%;
            height: 755pt;
            border-collapse: collapse;
            text-align: center;
            font-family: 'Times New Roman', Times, Georgia, serif;
            page-break-after: always;
        }

        .cover-top {
            vertical-align: top;
            padding-top: 15pt;
            height: 130pt;
        }

        .cover-title {
            font-size: 15pt;
            font-weight: bold;
            color: #000000;
            letter-spacing: 0.5px;
            margin: 0 0 6pt 0;
            text-transform: uppercase;
        }

        .cover-subtitle {
            font-size: 13pt;
            font-weight: bold;
            color: #000000;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .cover-mid {
            vertical-align: middle;
            padding: 10pt 0 40pt 0;
        }

        .cover-jabatan {
            font-size: 14pt;
            font-weight: bold;
            color: #000000;
            letter-spacing: 0.5px;
            margin-bottom: 28pt;
            text-transform: uppercase;
        }

        .cover-logo-wrapper {
            margin-bottom: 28pt;
        }

        .cover-logo-wrapper img {
            width: 115px;
            height: auto;
        }

        .cover-nama {
            font-size: 13pt;
            font-weight: bold;
            color: #000000;
        }

        .cover-nip {
            font-size: 11pt;
            color: #000000;
            margin-top: 3pt;
        }

        .cover-bottom {
            vertical-align: bottom;
            padding-bottom: 0;
            height: 190pt;
        }

        .instansi-bold {
            font-size: 10.5pt;
            font-weight: bold;
            color: #000000;
            margin-bottom: 2pt;
            line-height: 1.35;
            letter-spacing: 0.2px;
        }

        .instansi-regular {
            font-size: 10pt;
            color: #000000;
            margin-bottom: 1.5pt;
            line-height: 1.35;
        }

        .instansi-year {
            font-size: 11pt;
            font-weight: bold;
            color: #000000;
            margin-top: 3pt;
        }

        /* =====================================================
           ISI LAPORAN TERPADU
        ===================================================== */

        .isi {
            font-size: 11.5px;
        }

        .report-header-title {
            text-align: center;
            margin-bottom: 24px;
            color: #0e622b;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            border-bottom: 2px solid #0e622b;
            padding-bottom: 8px;
        }

        .bab-heading {
            font-size: 12.5px;
            font-weight: bold;
            color: #0e622b;
            margin-top: 22px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            page-break-after: avoid;
        }

        .sub-periode-header {
            font-size: 11.5px;
            font-weight: bold;
            color: #374151;
            margin-top: 14px;
            margin-bottom: 6px;
            page-break-after: avoid;
        }

        .bulan-nilai-badge {
            font-size: 10.5px;
            font-weight: normal;
            color: #4b5563;
            margin-left: 6px;
        }

        .content-paragraph {
            line-height: 1.65;
            margin: 0 0 10px 0;
            text-align: justify;
        }

        .content-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 10px;
            background-color: #ffffff;
            page-break-inside: avoid;
        }

        .content-card-title {
            margin: 0 0 5px 0;
            color: #0e622b;
            font-size: 11.5px;
            font-weight: bold;
        }

        .photo-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            page-break-inside: avoid;
        }

        .photo-table td {
            width: 50%;
            padding: 6px;
            text-align: center;
            vertical-align: top;
        }

        .photo-card {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 6px;
            background-color: #ffffff;
            text-align: center;
            page-break-inside: avoid;
        }

        .photo-card img {
            max-width: 220px;
            max-height: 150px;
            width: auto;
            height: auto;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            display: block;
            margin: 0 auto;
        }

        .photo-caption {
            font-size: 9.5px;
            color: #4b5563;
            margin-top: 5px;
            font-style: italic;
            line-height: 1.3;
        }

        .empty-note {
            color: #6b7280;
            font-style: italic;
            padding-left: 6px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    @php
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV'
        ];

        $kuartal = (int)($request->kuartal ?? 1);

        $kuartalRomawi =
            $romawi[$kuartal]
            ?? ($request->kuartal ?? 'I');

        $bulanAkhirKuartal = $kuartal * 3;

        $tahunLaporan = $request->tahun ?? ($laporanList->first()->tahun ?? date('Y'));

        // =========================================================
        // PARSE & KATEGORISASI KEGIATAN DARI SELURUH BULAN
        // =========================================================
        $pendahuluanList = [];
        $kegiatanBulananList = [];
        $kendalaList = [];
        $rencanaList = [];
        $penutupList = [];
        $totalFotoCount = 0;

        foreach ($laporanList as $lap) {
            $namaBulan = \Carbon\Carbon::create()->month($lap->bulan)->translatedFormat('F');
            $kegiatanBulanIni = [];

            foreach ($lap->kegiatanList as $item) {
                $rawText = trim($item->kegiatan);
                $lines = explode("\n", $rawText, 2);
                $firstLine = strtolower(trim($lines[0] ?? ''));

                if (str_contains($firstLine, 'pendahuluan')) {
                    $body = count($lines) > 1 ? trim($lines[1]) : $rawText;
                    $pendahuluanList[$lap->bulan] = [
                        'bulan'     => $namaBulan,
                        'bulan_num' => $lap->bulan,
                        'tahun'     => $lap->tahun,
                        'body'      => $body,
                    ];
                } elseif (str_contains($firstLine, 'kendala')) {
                    $body = count($lines) > 1 ? trim($lines[1]) : $rawText;
                    $kendalaList[] = [
                        'bulan' => $namaBulan,
                        'tahun' => $lap->tahun,
                        'body'  => $body,
                    ];
                } elseif (str_contains($firstLine, 'rencana')) {
                    $body = count($lines) > 1 ? trim($lines[1]) : $rawText;
                    $rencanaList[] = [
                        'bulan' => $namaBulan,
                        'tahun' => $lap->tahun,
                        'body'  => $body,
                    ];
                } elseif (str_contains($firstLine, 'penutup')) {
                    $body = count($lines) > 1 ? trim($lines[1]) : $rawText;
                    $penutupList[$lap->bulan] = [
                        'bulan'     => $namaBulan,
                        'bulan_num' => $lap->bulan,
                        'tahun'     => $lap->tahun,
                        'body'      => $body,
                    ];
                } else {
                    // Item Kegiatan yang dilaksanakan
                    $title = null;
                    $body = $rawText;
                    if (str_contains($firstLine, 'kegiatan yang telah') || str_contains($firstLine, 'kegiatan yang dilaksanakan')) {
                        $body = count($lines) > 1 ? trim($lines[1]) : $rawText;
                    } elseif (count($lines) > 1 && strlen($lines[0]) < 80) {
                        $title = trim($lines[0]);
                        $body = trim($lines[1]);
                    }

                    $fotoList = $item->pdf_foto_list ?? [];
                    $totalFotoCount += count($fotoList);

                    $kegiatanBulanIni[] = [
                        'title'         => $title,
                        'body'          => $body,
                        'pdf_foto_list' => $fotoList,
                    ];
                }
            }

            $kegiatanBulananList[] = [
                'bulan'     => $namaBulan,
                'tahun'     => $lap->tahun,
                'nilai'     => $lap->nilai,
                'items'     => $kegiatanBulanIni,
            ];
        }

        // Ambil pendahuluan & penutup dari bulan terakhir di kuartal (misal: Des / bulan 12 pada Triwulan 4)
        // Fallback ke bulan paling akhir yang tersedia jika di bulan terakhir belum diisi
        $pendahuluanTerakhir = $pendahuluanList[$bulanAkhirKuartal] ?? (count($pendahuluanList) > 0 ? end($pendahuluanList) : null);
        $penutupTerakhir = $penutupList[$bulanAkhirKuartal] ?? (count($penutupList) > 0 ? end($penutupList) : null);
    @endphp


    <!-- =====================================================
         HALAMAN SAMPUL / COVER
    ====================================================== -->

    <table class="cover-table">

        <tr>
            <td class="cover-top">

                <div class="cover-title">
                    LAPORAN KINERJA
                </div>

                <div class="cover-subtitle">
                    @if($isTahunan ?? false)
                        TAHUN {{ $tahunLaporan }}
                    @else
                        TRIWULAN {{ $kuartalRomawi }} TAHUN {{ $tahunLaporan }}
                    @endif
                </div>

            </td>
        </tr>


        <tr>
            <td class="cover-mid">

                <div class="cover-jabatan">
                    {{ strtoupper($pegawai->jabatan ?? 'OPERATOR LAYANAN OPERASIONAL') }}
                </div>

                <div class="cover-logo-wrapper">
                    @php
                        $logoPath = public_path('images/logo.png');
                        $logoKementanPath = public_path('images/logo-kementan.png');
                        $logoKementanJpg = public_path('images/logo-kementan.jpg');

                        $resolvedLogo = null;
                        if (file_exists($logoKementanPath)) {
                            $resolvedLogo = $logoKementanPath;
                        } elseif (file_exists($logoKementanJpg)) {
                            $resolvedLogo = $logoKementanJpg;
                        } elseif (file_exists($logoPath)) {
                            $resolvedLogo = $logoPath;
                        }
                    @endphp

                    @if($resolvedLogo)
                        <img src="{{ $resolvedLogo }}" alt="Logo Kementan">
                    @endif
                </div>

                <div class="cover-nama">
                    {{ $pegawai->nama }}
                </div>


                @if($pegawai->user?->nip)

                    <div class="cover-nip">
                        NIP {{ $pegawai->user->nip }}
                    </div>

                @endif

            </td>
        </tr>


        <tr>
            <td class="cover-bottom">

                <div class="instansi-bold">
                    BALAI PERAKITAN DAN PENGUJIAN AGROKLIMAT DAN HIDROLOGI PERTANIAN
                </div>

                <div class="instansi-regular">
                    Balai Besar Perakitan dan Modernisasi Sumberdaya Lahan Pertanian
                </div>

                <div class="instansi-regular">
                    Badan Perakitan dan Modernisasi Pertanian
                </div>

                <div class="instansi-regular">
                    Kementerian Pertanian
                </div>

                <div class="instansi-year">
                    {{ $tahunLaporan }}
                </div>

            </td>
        </tr>

    </table>


    <!-- =====================================================
         ISI LAPORAN TERPADU
    ====================================================== -->

    <div class="isi">

        <div class="report-header-title">
            @if($isTahunan ?? false)
                LAPORAN KINERJA TAHUN {{ $tahunLaporan }}
            @else
                LAPORAN KINERJA TRIWULAN {{ $kuartalRomawi }} TAHUN {{ $tahunLaporan }}
            @endif
        </div>


        <!-- =================================================
             I. PENDAHULUAN & TUJUAN
        ================================================== -->
        <div class="bab-heading">
            I. PENDAHULUAN &amp; TUJUAN
        </div>

        @if(!empty($pendahuluanTerakhir))
            <p class="content-paragraph">
                {!! nl2br(e(trim($pendahuluanTerakhir['body']))) !!}
            </p>
        @else
            <p class="content-paragraph">
                @if($isTahunan ?? false)
                    Laporan Kinerja Tahun {{ $tahunLaporan }} ini disusun sebagai wujud pertanggungjawaban atas pelaksanaan tugas, fungsi, dan capaian target kinerja pegawai selama periode 1 (satu) tahun berjalan.
                @else
                    Laporan Kinerja Triwulan {{ $kuartalRomawi }} Tahun {{ $tahunLaporan }} ini disusun sebagai wujud pertanggungjawaban atas pelaksanaan tugas, fungsi, dan capaian target kinerja pegawai selama periode 3 (tiga) bulan berjalan.
                @endif
            </p>
        @endif


        <!-- =================================================
             II. KEGIATAN YANG TELAH DILAKSANAKAN
        ================================================== -->
        <div class="bab-heading">
            II. KEGIATAN YANG TELAH DILAKSANAKAN
        </div>

        @php $adaKegiatan = false; @endphp

        @foreach ($kegiatanBulananList as $idxBulan => $bData)
            <div class="sub-periode-header">
                Periode {{ $bData['bulan'] }} {{ $bData['tahun'] }}:
            </div>

            @if(empty($bData['items']))
                <p class="empty-note">
                    Tidak ada catatan kegiatan pada bulan ini.
                </p>
            @else
                @php $adaKegiatan = true; @endphp
                @foreach ($bData['items'] as $itemIdx => $keg)
                    @if(!empty($keg['title']))
                        <div style="font-weight: bold; color: #1f2937; margin-top: 6px; margin-bottom: 3px;">
                            {{ ($itemIdx + 1) }}. {{ $keg['title'] }}
                        </div>
                    @endif

                    <p class="content-paragraph">
                        {!! nl2br(e(trim($keg['body']))) !!}
                    </p>
                @endforeach
            @endif
        @endforeach

        @if(!$adaKegiatan)
            <p class="empty-note">
                Belum ada kegiatan yang dilaporkan pada periode ini.
            </p>
        @endif


        <!-- =================================================
             III. KENDALA YANG DIHADAPI
        ================================================== -->
        <div class="bab-heading">
            III. KENDALA YANG DIHADAPI
        </div>

        @if(!empty($kendalaList))
            @foreach($kendalaList as $ken)
                <div class="sub-periode-header">
                    Periode {{ $ken['bulan'] }} {{ $ken['tahun'] }}:
                </div>
                <p class="content-paragraph">
                    {!! nl2br(e(trim($ken['body']))) !!}
                </p>
            @endforeach
        @else
            <p class="content-paragraph">
                @if($isTahunan ?? false)
                    Secara umum pelaksanaan tugas dan kegiatan selama Tahun {{ $tahunLaporan }} berjalan dengan baik dan tidak terdapat kendala operasional yang berarti.
                @else
                    Secara umum pelaksanaan tugas dan kegiatan selama Triwulan {{ $kuartalRomawi }} Tahun {{ $tahunLaporan }} berjalan dengan baik dan tidak terdapat kendala operasional yang berarti.
                @endif
            </p>
        @endif


        <!-- =================================================
             IV. RENCANA TINDAK LANJUT
        ================================================== -->
        <div class="bab-heading">
            IV. RENCANA TINDAK LANJUT
        </div>

        @if(!empty($rencanaList))
            @foreach($rencanaList as $ren)
                <div class="sub-periode-header">
                    Periode {{ $ren['bulan'] }} {{ $ren['tahun'] }}:
                </div>
                <p class="content-paragraph">
                    {!! nl2br(e(trim($ren['body']))) !!}
                </p>
            @endforeach
        @else
            <p class="content-paragraph">
                Melanjutkan dan mengoptimalkan seluruh program kerja dan tugas operasional sesuai dengan target kinerja yang telah ditetapkan pada periode berikutnya.
            </p>
        @endif


        <!-- =================================================
             V. PENUTUP
        ================================================== -->
        <div class="bab-heading">
            V. PENUTUP
        </div>

        @if(!empty($penutupTerakhir))
            <p class="content-paragraph">
                {!! nl2br(e(trim($penutupTerakhir['body']))) !!}
            </p>
        @else
            <p class="content-paragraph">
                @if($isTahunan ?? false)
                    Demikian Laporan Kinerja Tahun {{ $tahunLaporan }} ini disusun dengan sebenar-benarnya sebagai bahan evaluasi dan monitoring pelaksanaan tugas.
                @else
                    Demikian Laporan Kinerja Triwulan {{ $kuartalRomawi }} Tahun {{ $tahunLaporan }} ini disusun dengan sebenar-benarnya sebagai bahan evaluasi dan monitoring pelaksanaan tugas.
                @endif
            </p>
        @endif


        <!-- =================================================
             VI. DOKUMENTASI KEGIATAN
        ================================================== -->
        @if($totalFotoCount > 0)
            <div class="bab-heading">
                VI. DOKUMENTASI KEGIATAN
            </div>

            @foreach ($kegiatanBulananList as $idxBulan => $bData)
                @php
                    $fotoBulanIni = [];
                    foreach ($bData['items'] as $kItem) {
                        foreach ($kItem['pdf_foto_list'] as $fIdx => $fPath) {
                            if ($fPath && file_exists($fPath)) {
                                $fotoBulanIni[] = [
                                    'path'  => $fPath,
                                    'label' => 'Dokumentasi ' . $bData['bulan'] . ' ' . $bData['tahun'] . (count($kItem['pdf_foto_list']) > 1 ? ' (' . ($fIdx + 1) . ')' : ''),
                                ];
                            }
                        }
                    }
                @endphp

                @if(!empty($fotoBulanIni))
                    <div class="sub-periode-header">
                        Periode {{ $bData['bulan'] }} {{ $bData['tahun'] }}:
                    </div>

                    <table class="photo-table">
                        @foreach (array_chunk($fotoBulanIni, 2) as $fRow)
                            <tr>
                                @foreach ($fRow as $fItem)
                                    <td>
                                        <div class="photo-card">
                                            <img src="{{ $fItem['path'] }}" alt="Dokumentasi">
                                            <div class="photo-caption">{{ $fItem['label'] }}</div>
                                        </div>
                                    </td>
                                @endforeach
                                @if (count($fRow) === 1)
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endforeach
        @endif

    </div>

</body>
</html>