<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapitulasiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = Kehadiran::selectRaw('pegawai_id, status, jenis, count(*) as total, sum(menit_telat) as total_menit_telat');

        if (!empty($this->bulan) && $this->bulan !== 'semua') {
            $query->whereMonth('tanggal', (int)$this->bulan);
        }

        if (!empty($this->tahun) && $this->tahun !== 'semua') {
            $query->whereYear('tanggal', (int)$this->tahun);
        }

        return $query
            ->groupBy('pegawai_id', 'status', 'jenis')
            ->with('pegawai')
            ->get()
            ->groupBy('pegawai_id');
    }

    public function headings(): array
    {
        return [
            'Nama Pegawai',
            'Total Kehadiran',
            'WFO',
            'WFH',
            'Terlambat',
            'Akumulasi Telat (menit)',
            'Izin Lainnya',
            'Sakit',
            'Dinas Luar',
        ];
    }

    public function map($row): array
    {
        $nama = $row->first()->pegawai->nama ?? '-';

        $totalKehadiran = $row->whereIn('status', ['hadir', 'terlambat'])->sum('total');
        $wfo = $row->where('jenis', 'wfo')->sum('total');
        $wfh = $row->where('jenis', 'wfh')->sum('total');
        $terlambat = $row->where('status', 'terlambat')->sum('total');
        $totalMenitTelat = $row->sum('total_menit_telat');
        $izinLainnya = $row->where('status', 'izin')->sum('total');
        $sakit = $row->where('status', 'sakit')->sum('total');
        $dinas = $row->where('jenis', 'dinas')->sum('total');

        return [
            $nama,
            $totalKehadiran,
            $wfo,
            $wfh,
            $terlambat,
            $totalMenitTelat,
            $izinLainnya,
            $sakit,
            $dinas,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}