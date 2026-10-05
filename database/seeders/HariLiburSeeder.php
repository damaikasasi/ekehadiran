<?php

namespace Database\Seeders;

use App\Models\HariLibur;
use Illuminate\Database\Seeder;

class HariLiburSeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            // 2024
            ['tanggal' => '2024-01-01', 'nama_libur' => 'Tahun Baru 2024 Masehi', 'jenis' => 'nasional'],
            ['tanggal' => '2024-02-08', 'nama_libur' => 'Isra Mi\'raj Nabi Muhammad SAW', 'jenis' => 'nasional'],
            ['tanggal' => '2024-02-10', 'nama_libur' => 'Tahun Baru Imlek 2575 Kongzili', 'jenis' => 'nasional'],
            ['tanggal' => '2024-03-11', 'nama_libur' => 'Hari Suci Nyepi Tahun Baru Saka 1946', 'jenis' => 'nasional'],
            ['tanggal' => '2024-03-29', 'nama_libur' => 'Wafat Yesus Kristus', 'jenis' => 'nasional'],
            ['tanggal' => '2024-03-31', 'nama_libur' => 'Hari Paskah', 'jenis' => 'nasional'],
            ['tanggal' => '2024-04-10', 'nama_libur' => 'Hari Raya Idul Fitri 1445 H', 'jenis' => 'nasional'],
            ['tanggal' => '2024-04-11', 'nama_libur' => 'Hari Raya Idul Fitri 1445 H', 'jenis' => 'nasional'],
            ['tanggal' => '2024-05-01', 'nama_libur' => 'Hari Buruh Internasional', 'jenis' => 'nasional'],
            ['tanggal' => '2024-05-09', 'nama_libur' => 'Kenaikan Yesus Kristus', 'jenis' => 'nasional'],
            ['tanggal' => '2024-05-23', 'nama_libur' => 'Hari Raya Waisak 2568 BE', 'jenis' => 'nasional'],
            ['tanggal' => '2024-06-01', 'nama_libur' => 'Hari Lahir Pancasila', 'jenis' => 'nasional'],
            ['tanggal' => '2024-06-17', 'nama_libur' => 'Hari Raya Idul Adha 1445 H', 'jenis' => 'nasional'],
            ['tanggal' => '2024-07-07', 'nama_libur' => 'Tahun Baru Islam 1446 H', 'jenis' => 'nasional'],
            ['tanggal' => '2024-08-17', 'nama_libur' => 'Hari Kemerdekaan Republik Indonesia', 'jenis' => 'nasional'],
            ['tanggal' => '2024-09-16', 'nama_libur' => 'Maulid Nabi Muhammad SAW', 'jenis' => 'nasional'],
            ['tanggal' => '2024-12-25', 'nama_libur' => 'Hari Raya Natal', 'jenis' => 'nasional'],

            // 2025
            ['tanggal' => '2025-01-01', 'nama_libur' => 'Tahun Baru 2025 Masehi', 'jenis' => 'nasional'],
            ['tanggal' => '2025-01-27', 'nama_libur' => 'Isra Mi\'raj Nabi Muhammad SAW', 'jenis' => 'nasional'],
            ['tanggal' => '2025-01-29', 'nama_libur' => 'Tahun Baru Imlek 2576 Kongzili', 'jenis' => 'nasional'],
            ['tanggal' => '2025-03-29', 'nama_libur' => 'Hari Suci Nyepi (Tahun Baru Saka 1947)', 'jenis' => 'nasional'],
            ['tanggal' => '2025-03-31', 'nama_libur' => 'Hari Raya Idul Fitri 1446 H', 'jenis' => 'nasional'],
            ['tanggal' => '2025-04-01', 'nama_libur' => 'Hari Raya Idul Fitri 1446 H', 'jenis' => 'nasional'],
            ['tanggal' => '2025-04-18', 'nama_libur' => 'Wafat Yesus Kristus', 'jenis' => 'nasional'],
            ['tanggal' => '2025-04-20', 'nama_libur' => 'Kebangkitan Yesus Kristus (Paskah)', 'jenis' => 'nasional'],
            ['tanggal' => '2025-05-01', 'nama_libur' => 'Hari Buruh Internasional', 'jenis' => 'nasional'],
            ['tanggal' => '2025-05-12', 'nama_libur' => 'Hari Raya Waisak 2569 BE', 'jenis' => 'nasional'],
            ['tanggal' => '2025-05-29', 'nama_libur' => 'Kenaikan Yesus Kristus', 'jenis' => 'nasional'],
            ['tanggal' => '2025-06-01', 'nama_libur' => 'Hari Lahir Pancasila', 'jenis' => 'nasional'],
            ['tanggal' => '2025-06-06', 'nama_libur' => 'Hari Raya Idul Adha 1446 H', 'jenis' => 'nasional'],
            ['tanggal' => '2025-06-27', 'nama_libur' => 'Tahun Baru Islam 1447 H', 'jenis' => 'nasional'],
            ['tanggal' => '2025-08-17', 'nama_libur' => 'Hari Kemerdekaan Republik Indonesia', 'jenis' => 'nasional'],
            ['tanggal' => '2025-09-05', 'nama_libur' => 'Maulid Nabi Muhammad SAW', 'jenis' => 'nasional'],
            ['tanggal' => '2025-12-25', 'nama_libur' => 'Hari Raya Natal', 'jenis' => 'nasional'],

            // 2026
            ['tanggal' => '2026-01-01', 'nama_libur' => 'Tahun Baru 2026 Masehi', 'jenis' => 'nasional'],
            ['tanggal' => '2026-01-16', 'nama_libur' => 'Isra Mi\'raj Nabi Muhammad SAW', 'jenis' => 'nasional'],
            ['tanggal' => '2026-02-17', 'nama_libur' => 'Tahun Baru Imlek 2577 Kongzili', 'jenis' => 'nasional'],
            ['tanggal' => '2026-03-20', 'nama_libur' => 'Hari Suci Nyepi (Tahun Baru Saka 1948)', 'jenis' => 'nasional'],
            ['tanggal' => '2026-03-21', 'nama_libur' => 'Hari Raya Idul Fitri 1447 H', 'jenis' => 'nasional'],
            ['tanggal' => '2026-03-22', 'nama_libur' => 'Hari Raya Idul Fitri 1447 H', 'jenis' => 'nasional'],
            ['tanggal' => '2026-04-03', 'nama_libur' => 'Wafat Yesus Kristus', 'jenis' => 'nasional'],
            ['tanggal' => '2026-04-05', 'nama_libur' => 'Hari Paskah', 'jenis' => 'nasional'],
            ['tanggal' => '2026-05-01', 'nama_libur' => 'Hari Buruh Internasional', 'jenis' => 'nasional'],
            ['tanggal' => '2026-05-14', 'nama_libur' => 'Kenaikan Yesus Kristus', 'jenis' => 'nasional'],
            ['tanggal' => '2026-05-27', 'nama_libur' => 'Hari Raya Idul Adha 1447 H', 'jenis' => 'nasional'],
            ['tanggal' => '2026-05-31', 'nama_libur' => 'Hari Raya Waisak 2570 BE', 'jenis' => 'nasional'],
            ['tanggal' => '2026-06-01', 'nama_libur' => 'Hari Lahir Pancasila', 'jenis' => 'nasional'],
            ['tanggal' => '2026-06-16', 'nama_libur' => 'Tahun Baru Islam 1448 H', 'jenis' => 'nasional'],
            ['tanggal' => '2026-08-17', 'nama_libur' => 'Hari Kemerdekaan Republik Indonesia', 'jenis' => 'nasional'],
            ['tanggal' => '2026-08-25', 'nama_libur' => 'Maulid Nabi Muhammad SAW', 'jenis' => 'nasional'],
            ['tanggal' => '2026-12-24', 'nama_libur' => 'Cuti Bersama (Hari Raya Natal)', 'jenis' => 'cuti_bersama'],
            ['tanggal' => '2026-12-25', 'nama_libur' => 'Hari Raya Natal', 'jenis' => 'nasional'],

            // 2027
            ['tanggal' => '2027-01-01', 'nama_libur' => 'Tahun Baru 2027 Masehi', 'jenis' => 'nasional'],
            ['tanggal' => '2027-01-06', 'nama_libur' => 'Isra Mi\'raj Nabi Muhammad SAW', 'jenis' => 'nasional'],
            ['tanggal' => '2027-02-06', 'nama_libur' => 'Tahun Baru Imlek 2578 Kongzili', 'jenis' => 'nasional'],
            ['tanggal' => '2027-03-09', 'nama_libur' => 'Hari Suci Nyepi (Tahun Baru Saka 1949)', 'jenis' => 'nasional'],
            ['tanggal' => '2027-03-10', 'nama_libur' => 'Hari Raya Idul Fitri 1448 H', 'jenis' => 'nasional'],
            ['tanggal' => '2027-03-11', 'nama_libur' => 'Hari Raya Idul Fitri 1448 H', 'jenis' => 'nasional'],
            ['tanggal' => '2027-03-26', 'nama_libur' => 'Wafat Yesus Kristus', 'jenis' => 'nasional'],
            ['tanggal' => '2027-03-28', 'nama_libur' => 'Hari Paskah', 'jenis' => 'nasional'],
            ['tanggal' => '2027-05-01', 'nama_libur' => 'Hari Buruh Internasional', 'jenis' => 'nasional'],
            ['tanggal' => '2027-05-06', 'nama_libur' => 'Kenaikan Yesus Kristus', 'jenis' => 'nasional'],
            ['tanggal' => '2027-05-16', 'nama_libur' => 'Hari Raya Idul Adha 1448 H', 'jenis' => 'nasional'],
            ['tanggal' => '2027-05-20', 'nama_libur' => 'Hari Raya Waisak 2571 BE', 'jenis' => 'nasional'],
            ['tanggal' => '2027-06-01', 'nama_libur' => 'Hari Lahir Pancasila', 'jenis' => 'nasional'],
            ['tanggal' => '2027-06-06', 'nama_libur' => 'Tahun Baru Islam 1449 H', 'jenis' => 'nasional'],
            ['tanggal' => '2027-08-15', 'nama_libur' => 'Maulid Nabi Muhammad SAW', 'jenis' => 'nasional'],
            ['tanggal' => '2027-08-17', 'nama_libur' => 'Hari Kemerdekaan Republik Indonesia', 'jenis' => 'nasional'],
            ['tanggal' => '2027-12-25', 'nama_libur' => 'Hari Raya Natal', 'jenis' => 'nasional'],
        ];

        foreach ($holidays as $holiday) {
            HariLibur::updateOrCreate(
                ['tanggal' => $holiday['tanggal']],
                [
                    'nama_libur' => $holiday['nama_libur'],
                    'jenis'      => $holiday['jenis'],
                ]
            );
        }
    }
}
