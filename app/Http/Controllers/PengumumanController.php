<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    public function index()
    {
        $query = Pengumuman::query();

        // Pegawai/atasan hanya melihat pengumuman yang tanggalnya sudah tiba (<= hari ini)
        if (auth()->user()->role !== 'admin') {
            $query->whereDate('tanggal', '<=', today());
        }

        $pengumuman = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(10);
        return view('pengumuman.index', compact('pengumuman'));
    }

    public function show(Pengumuman $pengumuman)
    {
        // Proteksi jika pengumuman belum tiba tanggal publikasinya
        if (auth()->user()->role !== 'admin' && \Carbon\Carbon::parse($pengumuman->tanggal)->isFuture() && !\Carbon\Carbon::parse($pengumuman->tanggal)->isToday()) {
            abort(404, 'Pengumuman belum dipublikasikan.');
        }

        return view('pengumuman.show', compact('pengumuman'));
    }

    public function create()
    {
        return view('pengumuman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'tanggal' => 'required|date|after_or_equal:today',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ], [
            'tanggal.after_or_equal' => 'Tanggal publikasi tidak boleh tanggal yang sudah lewat.',
        ]);

        $filePath = null;
        if ($request->hasFile('file_lampiran')) {
            $filePath = $request->file('file_lampiran')->store('pengumuman', 'public');
        }

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
            'file_lampiran' => $filePath,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()
            ->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat dan dipublikasikan.');
    }

    // =========================
    // FORM EDIT
    // =========================
    public function edit(Pengumuman $pengumuman)
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    // =========================
    // UPDATE DATA
    // =========================
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'nullable|string|max:100',
            'tanggal' => 'required|date',
            'file_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
        ]);

        $filePath = $pengumuman->file_lampiran;
        if ($request->hasFile('file_lampiran')) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_lampiran')->store('pengumuman', 'public');
        }

        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
            'file_lampiran' => $filePath,
        ]);

        return redirect()
            ->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    // =========================
    // HAPUS DATA
    // =========================
    public function destroy(Pengumuman $pengumuman)
    {
        if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
            Storage::disk('public')->delete($pengumuman->file_lampiran);
        }

        $pengumuman->delete();

        return redirect()
            ->route('pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}