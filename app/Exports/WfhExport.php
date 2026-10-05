<?php

namespace App\Exports;

use App\Models\Kehadiran;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WfhExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    WithStyles,
    WithColumnWidths
{
    protected $bulan;
    protected $tahun;
    protected ?string $search;
    protected ?string $status;
    protected int $rowNumber = 0;

    public function __construct(
        $bulan = null,
        $tahun = null,
        ?string $search = null,
        ?string $status = null
    ) {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->search = $search;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Kehadiran::with([
            'pegawai:id,nama,divisi_id',
            'pegawai.divisi:id,nama_divisi',
            'pegawai.user:id,nip',
        ])->where('jenis', 'wfh');

        // Filter pencarian
        if (!empty($this->search)) {
            $search = $this->search;
            $query->whereHas('pegawai', function ($sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('nip', 'like', "%{$search}%");
                    })
                    ->orWhereHas('divisi', function ($d) use ($search) {
                        $d->where('nama_divisi', 'like', "%{$search}%");
                    });
            });
        }

        // Filter status
        if (!empty($this->status) && $this->status !== 'semua') {
            $query->where('status', $this->status);
        }

        // Filter bulan
        if (!empty($this->bulan) && $this->bulan !== 'semua') {
            $query->whereMonth('tanggal', (int) $this->bulan);
        }

        // Filter tahun
        if (!empty($this->tahun) && $this->tahun !== 'semua') {
            $query->whereYear('tanggal', (int) $this->tahun);
        }

        return $query
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_masuk', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'NIP',
            'Nama Pegawai',
            'Divisi',
            'Jam Masuk',
            'Jam Pulang',
            'Status Kehadiran',
            'Keterlambatan',
            'Pulang Cepat',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        $pegawai = $row->pegawai;
        $nip     = $pegawai?->user?->nip ?? '-';
        $nama    = $pegawai?->nama ?? '-';
        $divisi  = $pegawai?->divisi?->nama_divisi ?? '-';

        $tanggal = $row->tanggal ? Carbon::parse($row->tanggal)->translatedFormat('d F Y') : '-';

        $jamMasuk = $row->jam_masuk ? str_replace(':', '.', substr($row->jam_masuk, 0, 5)) : '-';
        $jamKeluar = $row->jam_keluar ? str_replace(':', '.', substr($row->jam_keluar, 0, 5)) : '-';

        $status = match ($row->status) {
            'hadir'               => 'Hadir (Terverifikasi)',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'terlambat'           => 'Terlambat',
            'alpha'               => 'Ditolak / Alpha',
            default               => ucfirst(str_replace('_', ' ', $row->status ?? '-')),
        };

        $keterlambatan = ($row->menit_telat > 0 || $row->status === 'terlambat')
            ? ($row->menit_telat ? "{$row->menit_telat} Menit" : 'Terlambat')
            : 'Tepat Waktu';

        $pulangCepat = $row->menit_pulang_awal > 0
            ? "{$row->menit_pulang_awal} Menit"
            : '-';

        return [
            $this->rowNumber,
            $tanggal,
            $nip,
            $nama,
            $divisi,
            $jamMasuk,
            $jamKeluar,
            $status,
            $keterlambatan,
            $pulangCepat,
            $row->keterangan ?: '-',
        ];
    }

    public function title(): string
    {
        $namaBulan = (!empty($this->bulan) && $this->bulan !== 'semua')
            ? Carbon::create()->month((int)$this->bulan)->translatedFormat('F')
            : 'Semua Bulan';

        $namaTahun = (!empty($this->tahun) && $this->tahun !== 'semua')
            ? $this->tahun
            : '';

        return trim("Presensi WFH {$namaBulan} {$namaTahun}");
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0B602B'],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 16,  // Tanggal
            'C' => 18,  // NIP
            'D' => 28,  // Nama Pegawai
            'E' => 22,  // Divisi
            'F' => 14,  // Jam Masuk
            'G' => 14,  // Jam Pulang
            'H' => 24,  // Status Kehadiran
            'I' => 18,  // Keterlambatan
            'J' => 16,  // Pulang Cepat
            'K' => 30,  // Keterangan
        ];
    }
}
