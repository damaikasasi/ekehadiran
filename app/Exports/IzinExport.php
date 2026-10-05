<?php

namespace App\Exports;

use App\Models\Izin;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IzinExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithTitle,
    WithStyles,
    WithColumnWidths
{
    protected ?string $search;
    protected ?string $jenis;
    protected ?string $status;
    protected ?string $bulan;
    protected ?string $tahun;
    protected $user;
    protected int $rowNumber = 0;

    public function __construct(
        ?string $search = null,
        $user = null,
        ?string $jenis = null,
        ?string $status = null,
        ?string $bulan = null,
        ?string $tahun = null
    ) {
        $this->search = $search;
        $this->user = $user ?? auth()->user();
        $this->jenis = $jenis;
        $this->status = $status;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = Izin::with([
            'pegawai:id,nama,divisi_id',
            'pegawai.divisi:id,nama_divisi',
            'pegawai.user:id,nip',
        ]);

        // Filter berdasarkan role
        if ($this->user) {

            // Pegawai/User hanya melihat izin miliknya
            if ($this->user->role === 'user') {

                $pegawaiId = $this->user->pegawai?->id;

                if ($pegawaiId) {
                    $query->where('pegawai_id', $pegawaiId);
                }

            // Atasan hanya melihat izin pegawai di divisinya
            } elseif ($this->user->role === 'atasan') {

                $atasanPegawai = $this->user->pegawai;

                if ($atasanPegawai) {
                    $query->whereHas('pegawai', function ($q) use ($atasanPegawai) {
                        $q->where('divisi_id', $atasanPegawai->divisi_id);
                    });
                }
            }
        }

        // Search
        if (!empty($this->search)) {

            $search = $this->search;

            if ($this->user && $this->user->role === 'user') {

                $query->where(function ($q) use ($search) {
                    $q->where('keterangan', 'like', "%{$search}%")
                      ->orWhere('jenis', 'like', "%{$search}%");
                });

            } else {

                $query->where(function ($q) use ($search) {

                    $q->whereHas('pegawai', function ($sub) use ($search) {

                        $sub->where('nama', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($u) use ($search) {
                                $u->where('nip', 'like', "%{$search}%");
                            })
                            ->orWhereHas('divisi', function ($d) use ($search) {
                                $d->where('nama_divisi', 'like', "%{$search}%");
                            });

                    })
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%");
                });
            }
        }

        // Filter jenis izin
        if (!empty($this->jenis) && $this->jenis !== 'semua') {
            $query->where('jenis', $this->jenis);
        }

        // Filter status
        if (!empty($this->status) && $this->status !== 'semua') {
            $query->where('status', $this->status);
        }

        // Filter bulan
        if (!empty($this->bulan) && $this->bulan !== 'semua') {
            $query->whereMonth('tanggal_mulai', (int) $this->bulan);
        }

        // Filter tahun
        if (!empty($this->tahun) && $this->tahun !== 'semua') {
            $query->whereYear('tanggal_mulai', (int) $this->tahun);
        }

        return $query
            ->latest('tanggal_mulai')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIP',
            'Nama Pegawai',
            'Divisi',
            'Jenis Izin',
            'Surat Dokter',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Total Hari',
            'Keterangan',
            'Status',
            'Tanggal Pengajuan',
        ];
    }

    public function map($row): array
    {
        $this->rowNumber++;

        $pegawai = $row->pegawai;

        $nip = $pegawai?->user?->nip ?? '-';
        $nama = $pegawai?->nama ?? '-';
        $divisi = $pegawai?->divisi?->nama_divisi ?? '-';

        // Jenis izin
        $jenisIzin = match ($row->jenis) {
            'cuti_tahunan' => 'Cuti Tahunan',
            'sakit' => 'Sakit',
            'dinas' => 'Dinas Luar',
            default => ucfirst(str_replace('_', ' ', $row->jenis)),
        };

        // Surat dokter
        $suratDokter = $row->jenis === 'sakit'
            ? ($row->ada_surat_dokter ? 'Ada' : 'Tidak Ada')
            : '-';

        // Tanggal
        $tglMulai = Carbon::parse($row->tanggal_mulai);
        $tglSelesai = Carbon::parse($row->tanggal_selesai);

        // Total hari
        $totalHari = $tglMulai->diffInDays($tglSelesai) + 1;

        // Status
        $status = match ($row->status) {
            'pending' => 'Menunggu Verifikasi Atasan',
            'disetujui_atasan' => 'Menunggu Verifikasi Admin',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($row->status),
        };

        return [
            $this->rowNumber,
            $nip,
            $nama,
            $divisi,
            $jenisIzin,
            $suratDokter,
            $tglMulai->translatedFormat('d F Y'),
            $tglSelesai->translatedFormat('d F Y'),
            $totalHari . ' hari',
            $row->keterangan ?: '-',
            $status,
            $row->created_at
                ? Carbon::parse($row->created_at)->translatedFormat('d F Y H:i')
                : '-',
        ];
    }

    public function title(): string
    {
        return 'Rincian Pengajuan Izin';
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
            'A' => 6,
            'B' => 18,
            'C' => 25,
            'D' => 20,
            'E' => 22,
            'F' => 15,
            'G' => 18,
            'H' => 18,
            'I' => 12,
            'J' => 40,
            'K' => 28,
            'L' => 22,
        ];
    }
}