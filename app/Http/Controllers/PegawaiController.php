<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Divisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = Pegawai::with(['divisi', 'user']);

        // Jika login sebagai Atasan, batasi hanya melihat pegawai di divisi sendiri
        if (auth()->user()->role === 'atasan') {
            $divisiId = auth()->user()->pegawai?->divisi_id;
            $query->where('divisi_id', $divisiId);
        } else {
            // Filter divisi (khusus admin)
            if ($request->filled('divisi_id')) {
                $query->where('divisi_id', $request->divisi_id);
            }
        }

        // Filter pencarian nama atau NIP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('nip', 'like', '%'.$search.'%');
                  });
            });
        }

        // Filter peran (role ada di tabel users)
        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('user', function ($uq) use ($role) {
                $uq->where('role', $role);
            });
        }

        $pegawai = $query->latest()->paginate(10)->withQueryString();
        $divisiList = Divisi::all();

        return view('pegawai.index', compact('pegawai', 'divisiList'));
    }

    public function create()
    {
        $divisi = Divisi::all();
        return view('pegawai.create', compact('divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => ['required', 'string', 'digits:18', 'unique:users,nip'],
            'nama' => ['required', 'string', 'max:255', 'regex:/^(?=.*[\pL])[\pL\s.,\'-]+$/u'],
            'divisi_id' => 'required|exists:divisi,id',
            'jabatan' => 'nullable|string|max:255',
            'struktur_fungsi' => 'nullable|string',
            'capaian_kerja' => 'required|string',
            'no_hp' => 'nullable|numeric|digits_between:8,20',
            'email' => 'nullable|email|unique:users,email',
            'tanggal_masuk' => 'nullable|date',
            'role' => 'required|in:admin,user,atasan',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'nip.unique' => 'NIP sudah terdaftar dalam sistem.',
            'divisi_id.required' => 'Divisi wajib dipilih.',
            'divisi_id.exists' => 'Divisi yang dipilih tidak valid.',
            'role.required' => 'Role akun wajib dipilih.',
            'role.in' => 'Role akun yang dipilih tidak valid.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.regex' => 'Nama harus mengandung huruf, tidak boleh mengandung angka, dan tidak boleh hanya karakter simbol.',
            'capaian_kerja.required' => 'Capaian kerja wajib diisi.',
            'no_hp.numeric' => 'No. HP harus berupa angka.',
            'no_hp.digits_between' => 'No. HP harus antara 8 sampai 20 digit angka.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        DB::transaction(function () use ($request) {
            // Jika role yang dipilih adalah atasan dan divisi dipilih,
            // atasan sebelumnya di divisi tersebut diturunkan/tertimpa menjadi 'user' agar tidak dobel
            if ($request->role === 'atasan' && $request->filled('divisi_id')) {
                User::where('role', 'atasan')
                    ->whereHas('pegawai', function ($q) use ($request) {
                        $q->where('divisi_id', $request->divisi_id);
                    })
                    ->update(['role' => 'user']);

                // Sinkronkan nama ketua divisi di tabel divisi
                Divisi::where('id', $request->divisi_id)->update([
                    'ketua_divisi' => $request->nama
                ]);
            }

            // 1. Buat data pegawai
            $pegawai = Pegawai::create([
                'divisi_id' => $request->divisi_id,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'struktur_fungsi' => $request->struktur_fungsi,
                'capaian_kerja' => $request->capaian_kerja,
                'no_hp' => $request->no_hp,
                'email' => $request->email ?: null,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_aktif' => 'aktif',
            ]);

            // 2. Buat akun login otomatis (NIP sebagai username, password awal = NIP full)
            User::create([
                'name' => $request->nama,
                'nip' => $request->nip,
                'pegawai_id' => $pegawai->id,
                'email' => $request->email ?: null,
                'password' => Hash::make($request->nip),
                'must_change_password' => true,
                'role' => $request->role,
            ]);
        });

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan. Password awal = NIP full.');
    }

    public function show(Pegawai $pegawai)
    {
        if (auth()->user()->role === 'atasan') {
            $divisiId = auth()->user()->pegawai?->divisi_id;
            if ($pegawai->divisi_id !== $divisiId) {
                abort(403, 'Anda tidak memiliki akses ke data pegawai di luar divisi Anda.');
            }
        }

        $pegawai->load(['divisi', 'user']);
        return view('pegawai.show', compact('pegawai'));
    }

    public function edit(Pegawai $pegawai)
    {
        $divisi = Divisi::all();
        $pegawai->load(['divisi', 'user']);
        return view('pegawai.edit', compact('pegawai', 'divisi'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $user = $pegawai->user ?: \App\Models\User::where('pegawai_id', $pegawai->id)->first();
        $userId = $user ? $user->id : null;

        $request->validate([
            'nip' => [
                'required',
                'string',
                'digits:18',
                Rule::unique('users', 'nip')->ignore($userId),
            ],
            'nama' => ['required', 'string', 'max:255', 'regex:/^(?=.*[\pL])[\pL\s.,\'-]+$/u'],
            'divisi_id' => 'required|exists:divisi,id',
            'jabatan' => 'nullable|string|max:255',
            'role' => 'required|in:admin,user,atasan',
            'status_aktif' => 'nullable|in:aktif,nonaktif,1,0',
            'struktur_fungsi' => 'nullable|string',
            'capaian_kerja' => 'required|string',
            'no_hp' => 'nullable|numeric|digits_between:8,20',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'tanggal_masuk' => 'nullable|date',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits' => 'NIP harus terdiri dari 18 digit angka.',
            'nip.numeric' => 'NIP harus berupa angka.',
            'nip.unique' => 'NIP sudah terdaftar dalam sistem.',
            'divisi_id.required' => 'Divisi wajib dipilih.',
            'divisi_id.exists' => 'Divisi yang dipilih tidak valid.',
            'role.required' => 'Role akun wajib dipilih.',
            'role.in' => 'Role akun yang dipilih tidak valid.',
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.regex' => 'Nama harus mengandung huruf, tidak boleh mengandung angka, dan tidak boleh hanya karakter simbol.',
            'capaian_kerja.required' => 'Capaian kerja wajib diisi.',
            'no_hp.numeric' => 'No. HP harus berupa angka.',
            'no_hp.digits_between' => 'No. HP harus antara 8 sampai 20 digit angka.',
            'email.unique' => 'Email sudah digunakan.',
        ]);

        DB::transaction(function () use ($request, $pegawai, $user) {
            $pegawaiData = $request->only('divisi_id', 'nama', 'jabatan', 'struktur_fungsi', 'capaian_kerja', 'no_hp', 'tanggal_masuk');
            if ($request->has('status_aktif')) {
                $statusVal = $request->input('status_aktif');
                $pegawaiData['status_aktif'] = in_array($statusVal, ['1', 1, 'aktif', true], true) ? 'aktif' : 'nonaktif';
            }
            $pegawaiData['email'] = $request->filled('email') ? $request->email : null;
            $pegawai->update($pegawaiData);

            $targetDivisiId = $request->input('divisi_id', $pegawai->divisi_id);

            // Jika role diubah menjadi atasan dan ada divisi yang ditentukan:
            if ($request->role === 'atasan' && !empty($targetDivisiId)) {
                // Turunkan atasan lama di divisi yang sama menjadi 'user' agar tidak dobel
                $queryOld = User::where('role', 'atasan')
                    ->whereHas('pegawai', function ($q) use ($targetDivisiId) {
                        $q->where('divisi_id', $targetDivisiId);
                    });
                if ($user) {
                    $queryOld->where('id', '!=', $user->id);
                }
                $queryOld->update(['role' => 'user']);

                // Sinkronkan nama ketua divisi
                Divisi::where('id', $targetDivisiId)->update([
                    'ketua_divisi' => $pegawaiData['nama'] ?? $pegawai->nama
                ]);
            }

            // Update User account role & details (termasuk NIP)
            if ($user) {
                $userUpdate = [];
                if ($request->filled('nip')) {
                    $userUpdate['nip'] = $request->nip;
                }
                if ($request->filled('role')) {
                    $userUpdate['role'] = $request->role;
                }
                if ($request->filled('nama')) {
                    $userUpdate['name'] = $request->nama;
                }
                $userUpdate['email'] = $request->filled('email') ? $request->email : null;
                if (!empty($userUpdate)) {
                    $user->update($userUpdate);
                }
            }
        });

        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete(); // otomatis set null di users.pegawai_id (sesuai onDelete di migration)
        return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}