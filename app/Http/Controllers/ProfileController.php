<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->filled('email') ? $request->email : null;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->pegawai) {
            $pegawaiData = [
                'nama' => $user->name,
                'email' => $user->email,
            ];

            if ($request->hasFile('foto')) {
                if ($user->pegawai->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->pegawai->foto)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->pegawai->foto);
                }

                $fotoPath = $request->file('foto')->store('pegawai-foto', 'public');
                $pegawaiData['foto'] = $fotoPath;
            }

            if ($request->has('capaian_kerja') && $user->role === 'admin') {
                $pegawaiData['capaian_kerja'] = $request->capaian_kerja;
            }

            $user->pegawai->update($pegawaiData);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
