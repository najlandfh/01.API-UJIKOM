<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ==========================================
    // PROFIL
    // ==========================================
    public function profile()
    {
        $user = auth()->user();

        return view('profile.index', compact('user'));
    }

    // ==========================================
    // FORM EDIT PROFIL
    // ==========================================
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    // ==========================================
    // UPDATE PROFIL
    // ==========================================
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];

        // Jika user memilih foto baru
        if ($request->hasFile('foto')) {

            // Simpan foto baru
            $path = $request->file('foto')->store('profile', 'public');

            // Hapus foto lama
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $path;
        }

        $user->update($data);

        return redirect()
            ->route($user->role . '.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}