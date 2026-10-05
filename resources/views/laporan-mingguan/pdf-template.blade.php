<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kinerja Bulanan - {{ $laporan->pegawai->nama ?? 'Pegawai' }}</title>

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

        .cover-tahun {
            font-size: 13pt;
            font-weight: bold;
            color: #000000;
            margin-top: 4pt;
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
           CONTENT STYLES
        ===================================================== */

        .section-block {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 6px;
        }

        .section-body {
            padding: 0;
            color: #1f2937;
            font-size: 11.5px;
            text-align: justify;
            line-height: 1.6;
        }

        .section-body p {
            margin: 0 0 6px 0;
        }

        /* =====================================================
           PHOTO GRID
        ===================================================== */

        .photo-grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .photo-grid-table td {
            padding: 5px;
            vertical-align: top;
        }

        .photo-card {
            background-color: #fafafa;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
            page-break-inside: avoid;
        }

        .photo-card img {
            max-width: 220px;
            max-height: 155px;
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

        /* =====================================================
           REVIEW
        ===================================================== */

        .review-box {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 10px 12px;
            margin-top: 18px;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .review-title {
            font-weight: bold;
            color: #166534;
            font-size: 11.5px;
            margin-bottom: 4px;
        }
    </style>
</head>

<body>

    @php
        $bulanNama =
            \Carbon\Carbon::create()
                ->month((int) $laporan->bulan)
                ->translatedFormat('F');

        $fotoList = [];

        $sectionCounter = 1;
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
                    BULAN {{ strtoupper($bulanNama) }} TAHUN {{ $laporan->tahun }}
                </div>

            </td>
        </tr>


        <tr>
            <td class="cover-mid">

                <div class="cover-jabatan">
                    {{ strtoupper($laporan->pegawai->jabatan ?? 'OPERATOR LAYANAN OPERASIONAL') }}
                </div>


                <div class="cover-logo-wrapper">

                    <img
                        src="{{ public_path('images/logo.png') }}"
                        alt="Logo"
                    >

                </div>


                <div class="cover-nama">
                    {{ $laporan->pegawai->nama }}
                </div>


                @if($laporan->pegawai->user?->nip)

                    <div class="cover-nip">
                        NIP {{ $laporan->pegawai->user->nip }}
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
                    {{ $laporan->tahun }}
                </div>

            </td>
        </tr>

    </table>


    <!-- =====================================================
         HALAMAN ISI LAPORAN
    ====================================================== -->

    @foreach ($laporan->kegiatanList as $i => $item)

        @php

            $lines =
                explode(
                    "\n",
                    trim($item->kegiatan),
                    2
                );

            $hasExplicitTitle =
                (
                    count($lines) > 1 &&
                    strlen($lines[0]) < 80
                );

            $rawTitle =
                $hasExplicitTitle
                    ? $lines[0]
                    : ('Kegiatan ' . ($i + 1));

            $cleanTitle =
                preg_replace(
                    '/^\d+[\.\)]\s*/',
                    '',
                    trim($rawTitle)
                );

            $sectionTitle =
                $sectionCounter .
                '. ' .
                $cleanTitle;

            $sectionBody =
                $hasExplicitTitle
                    ? $lines[1]
                    : $item->kegiatan;

            $sectionCounter++;


            /*
            |--------------------------------------------------------------------------
            | AMBIL GAMBAR HASIL OPTIMASI CONTROLLER
            |--------------------------------------------------------------------------
            |
            | Controller sekarang membuat attribute:
            |
            | $item->pdf_foto_list
            |
            | Isinya adalah path absolut menuju gambar yang sudah:
            | - resize
            | - compress
            | - disimpan dalam cache
            |
            */

            $pdfFotoList =
                $item->pdf_foto_list ?? [];


            /*
            |--------------------------------------------------------------------------
            | Masukkan foto ke daftar dokumentasi
            |--------------------------------------------------------------------------
            */

            if (!empty($pdfFotoList)) {

                foreach (
                    $pdfFotoList as $fIdx => $fPath
                ) {

                    if (
                        $fPath &&
                        file_exists($fPath)
                    ) {

                        $label =
                            count($pdfFotoList) > 1
                                ? (
                                    $cleanTitle .
                                    ' (' .
                                    ($fIdx + 1) .
                                    ')'
                                )
                                : $cleanTitle;

                        $fotoList[] = [

                            'path' =>
                                $fPath,

                            'label' =>
                                $label
                        ];
                    }
                }
            }

        @endphp


        <!-- =================================================
             SECTION KEGIATAN
        ================================================== -->

        <div class="section-block">

            <div class="section-title">
                {{ $sectionTitle }}
            </div>


            <div class="section-body">

                <p>
                    {!! nl2br(
                        e(
                            trim($sectionBody)
                        )
                    ) !!}
                </p>

            </div>

        </div>

    @endforeach


    <!-- =====================================================
         DOKUMENTASI
    ====================================================== -->

    @if(count($fotoList) > 0)

        <div
            class="section-block"
            style="margin-top: 18px;"
        >

            <div class="section-title">
                {{ $sectionCounter }}. Dokumentasi
            </div>


            <table class="photo-grid-table">

                @foreach(
                    array_chunk($fotoList, 2)
                    as $row
                )

                    <tr>

                        @foreach($row as $foto)

                            <td style="width: 50%;">

                                <div class="photo-card">

                                    <img
                                        src="{{ $foto['path'] }}"
                                        alt="Dokumentasi"
                                    >

                                    <div class="photo-caption">
                                        {{ $foto['label'] }}
                                    </div>

                                </div>

                            </td>

                        @endforeach


                        @if(count($row) === 1)

                            <td style="width: 50%;"></td>

                        @endif

                    </tr>

                @endforeach

            </table>

        </div>

    @endif

</body>
</html>