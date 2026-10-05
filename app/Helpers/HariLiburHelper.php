<?php

namespace App\Helpers;

use Carbon\Carbon;

class HariLiburHelper
{
    /**
     * Daftar tanggal merah tetap tahunan (Bulan-Hari)
     */
    protected static array $liburTetap = [
        '01-01' => 'Tahun Baru Masehi',
        '05-01' => 'Hari Buruh Internasional',
        '06-01' => 'Hari Lahir Pancasila',
        '08-17' => 'Hari Kemerdekaan Republik Indonesia',
        '12-25' => 'Hari Raya Natal',
    ];

    /**
     * Daftar hari libur nasional Indonesia berdasarkan tahun dan tanggal spesifik (Y-m-d)
     */
    protected static array $liburNasional = [
        // Tahun 2024
        '2024-01-01' => 'Tahun Baru 2024 Masehi',
        '2024-02-08' => 'Isra Mi\'raj Nabi Muhammad SAW',
        '2024-02-10' => 'Tahun Baru Imlek 2575 Kongzili',
        '2024-03-11' => 'Hari Suci Nyepi Tahun Baru Saka 1946',
        '2024-03-29' => 'Wafat Yesus Kristus',
        '2024-03-31' => 'Hari Paskah',
        '2024-04-10' => 'Hari Raya Idul Fitri 1445 H',
        '2024-04-11' => 'Hari Raya Idul Fitri 1445 H',
        '2024-05-01' => 'Hari Buruh Internasional',
        '2024-05-09' => 'Kenaikan Yesus Kristus',
        '2024-05-23' => 'Hari Raya Waisak 2568 BE',
        '2024-06-01' => 'Hari Lahir Pancasila',
        '2024-06-17' => 'Hari Raya Idul Adha 1445 H',
        '2024-07-07' => 'Tahun Baru Islam 1446 H',
        '2024-08-17' => 'Hari Kemerdekaan Republik Indonesia',
        '2024-09-16' => 'Maulid Nabi Muhammad SAW',
        '2024-12-25' => 'Hari Raya Natal',

        // Tahun 2025
        '2025-01-01' => 'Tahun Baru 2025 Masehi',
        '2025-01-27' => 'Isra Mi\'raj Nabi Muhammad SAW',
        '2025-01-29' => 'Tahun Baru Imlek 2576 Kongzili',
        '2025-03-29' => 'Hari Suci Nyepi (Tahun Baru Saka 1947)',
        '2025-03-31' => 'Hari Raya Idul Fitri 1446 H',
        '2025-04-01' => 'Hari Raya Idul Fitri 1446 H',
        '2025-04-18' => 'Wafat Yesus Kristus',
        '2025-04-20' => 'Kebangkitan Yesus Kristus (Paskah)',
        '2025-05-01' => 'Hari Buruh Internasional',
        '2025-05-12' => 'Hari Raya Waisak 2569 BE',
        '2025-05-29' => 'Kenaikan Yesus Kristus',
        '2025-06-01' => 'Hari Lahir Pancasila',
        '2025-06-06' => 'Hari Raya Idul Adha 1446 H',
        '2025-06-27' => 'Tahun Baru Islam 1447 H',
        '2025-08-17' => 'Hari Kemerdekaan Republik Indonesia',
        '2025-09-05' => 'Maulid Nabi Muhammad SAW',
        '2025-12-25' => 'Hari Raya Natal',

        // Tahun 2026
        '2026-01-01' => 'Tahun Baru 2026 Masehi',
        '2026-01-16' => 'Isra Mi\'raj Nabi Muhammad SAW',
        '2026-02-17' => 'Tahun Baru Imlek 2577 Kongzili',
        '2026-03-20' => 'Hari Suci Nyepi (Tahun Baru Saka 1948)',
        '2026-03-21' => 'Hari Raya Idul Fitri 1447 H',
        '2026-03-22' => 'Hari Raya Idul Fitri 1447 H',
        '2026-04-03' => 'Wafat Yesus Kristus',
        '2026-04-05' => 'Hari Paskah',
        '2026-05-01' => 'Hari Buruh Internasional',
        '2026-05-14' => 'Kenaikan Yesus Kristus',
        '2026-05-27' => 'Hari Raya Idul Adha 1447 H',
        '2026-05-31' => 'Hari Raya Waisak 2570 BE',
        '2026-06-01' => 'Hari Lahir Pancasila',
        '2026-06-16' => 'Tahun Baru Islam 1448 H',
        '2026-08-17' => 'Hari Kemerdekaan Republik Indonesia',
        '2026-08-25' => 'Maulid Nabi Muhammad SAW',
        '2026-12-24' => 'Cuti Bersama (Hari Raya Natal)',
        '2026-12-25' => 'Hari Raya Natal',

        // Tahun 2027
        '2027-01-01' => 'Tahun Baru 2027 Masehi',
        '2027-01-06' => 'Isra Mi\'raj Nabi Muhammad SAW',
        '2027-02-06' => 'Tahun Baru Imlek 2578 Kongzili',
        '2027-03-09' => 'Hari Suci Nyepi (Tahun Baru Saka 1949)',
        '2027-03-10' => 'Hari Raya Idul Fitri 1448 H',
        '2027-03-11' => 'Hari Raya Idul Fitri 1448 H',
        '2027-03-26' => 'Wafat Yesus Kristus',
        '2027-03-28' => 'Hari Paskah',
        '2027-05-01' => 'Hari Buruh Internasional',
        '2027-05-06' => 'Kenaikan Yesus Kristus',
        '2027-05-16' => 'Hari Raya Idul Adha 1448 H',
        '2027-05-20' => 'Hari Raya Waisak 2571 BE',
        '2027-06-01' => 'Hari Lahir Pancasila',
        '2027-06-06' => 'Tahun Baru Islam 1449 H',
        '2027-08-15' => 'Maulid Nabi Muhammad SAW',
        '2027-08-17' => 'Hari Kemerdekaan Republik Indonesia',
        '2027-12-25' => 'Hari Raya Natal',
    ];

    protected static ?array $dbCache = null;

    /**
     * Dapatkan nama hari libur nasional / khusus berdasarkan tanggal (Y-m-d atau Carbon)
     */
    public static function getLiburNasional($date): ?string
    {
        $carbonDate = $date instanceof Carbon ? $date : Carbon::parse($date);
        $ymd = $carbonDate->format('Y-m-d');
        $md  = $carbonDate->format('m-d');

        // 1. Cek dari database (tabel hari_liburs)
        try {
            if (self::$dbCache === null) {
                if (\Illuminate\Support\Facades\Schema::hasTable('hari_liburs')) {
                    self::$dbCache = \App\Models\HariLibur::pluck('nama_libur', 'tanggal')
                        ->mapWithKeys(function ($nama, $tgl) {
                            $key = $tgl instanceof Carbon ? $tgl->format('Y-m-d') : substr((string) $tgl, 0, 10);
                            return [$key => $nama];
                        })
                        ->toArray();
                } else {
                    self::$dbCache = [];
                }
            }

            if (isset(self::$dbCache[$ymd])) {
                return self::$dbCache[$ymd];
            }
        } catch (\Throwable $e) {
            // Fallback jika database belum siap
        }

        // 2. Fallback: Cek dari daftar spesifik tahun statis
        if (isset(self::$liburNasional[$ymd])) {
            return self::$liburNasional[$ymd];
        }

        // 3. Fallback: Cek dari daftar libur tetap tahunan
        if (isset(self::$liburTetap[$md])) {
            return self::$liburTetap[$md];
        }

        return null;
    }

    /**
     * Cek apakah tanggal tertentu adalah hari libur
     */
    public static function isLiburNasional($date): bool
    {
        return self::getLiburNasional($date) !== null;
    }

    /**
     * Dapatkan semua hari libur dalam satu bulan tertentu
     */
    public static function getLiburByMonth(int $bulan, int $tahun): array
    {
        $daysInMonth = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $result = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::createFromDate($tahun, $bulan, $d)->format('Y-m-d');
            $libur = self::getLiburNasional($date);
            if ($libur) {
                $result[$date] = $libur;
            }
        }

        return $result;
    }

    /**
     * Reset cache memori saat ada perubahan data di database
     */
    public static function clearCache(): void
    {
        self::$dbCache = null;
    }
}
