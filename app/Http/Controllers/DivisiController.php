<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DivisiController extends Controller
{
    public function index(Request $request)
    {
        $query = Divisi::withCount('pegawai')->latest();

        if ($request->filled('search')) {
            $query->where('nama_divisi', 'like', '%' . $request->search . '%');
        }

        $divisi = $query->paginate(10)->appends($request->query());
        return view('divisi.index', compact('divisi'));
    }

    public function create()
    {
        return view('divisi.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'nama_divisi' => is_string($request->nama_divisi) ? trim($request->nama_divisi) : $request->nama_divisi,
        ]);

        $request->validate([
            'nama_divisi' => [
                'required',
                'string',
                'max:255',
                Rule::unique('divisi', 'nama_divisi'),
            ],
        ], [
            'nama_divisi.required' => 'Nama divisi wajib diisi.',
            'nama_divisi.unique' => 'Nama divisi sudah terdaftar. Silakan gunakan nama divisi yang lain.',
        ]);

        Divisi::create([
            'nama_divisi' => $request->nama_divisi,
        ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil ditambahkan.');
    }

    public function edit(Divisi $divisi)
    {
        return view('divisi.edit', compact('divisi'));
    }

    public function update(Request $request, Divisi $divisi)
    {
        $request->merge([
            'nama_divisi' => is_string($request->nama_divisi) ? trim($request->nama_divisi) : $request->nama_divisi,
        ]);

        $request->validate([
            'nama_divisi' => [
                'required',
                'string',
                'max:255',
                Rule::unique('divisi', 'nama_divisi')->ignore($divisi->id),
            ],
        ], [
            'nama_divisi.required' => 'Nama divisi wajib diisi.',
            'nama_divisi.unique' => 'Nama divisi sudah ada. Silakan gunakan nama divisi yang lain.',
        ]);

        $divisi->update([
            'nama_divisi' => $request->nama_divisi,
        ]);

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil dihapus.');
    }
}