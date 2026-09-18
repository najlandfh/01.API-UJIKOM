<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamController extends Controller
{
    // ==========================================
    // PROFIL PEMINJAM
    // ==========================================

    public function profile()
    {
        $user = auth()->user();

        return view('peminjam.profile', compact('user'));
    }

    // ==========================================
    // EDIT PROFIL PEMINJAM
    // ==========================================

    public function editProfile()
    {
        $user = auth()->user();

        return view('peminjam.profile-edit', compact('user'));
    }

    // ==========================================
    // UPDATE PROFIL PEMINJAM
    // ==========================================

    public function updateProfile(Request $request)
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

        // Jika memilih foto baru
        if ($request->hasFile('foto')) {

            // Simpan foto baru
            $path = $request->file('foto')->store('profile', 'public');

            // Hapus foto lama jika ada
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $path;
        }

        $user->update($data);

        return redirect()
            ->route('peminjam.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }


    // ==========================================
    // KATALOG ALAT
    // ==========================================

    public function katalogAlat()
    {
        $alats = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->get();

        return view('peminjam.katalog', compact('alats'));
    }


    // ==========================================
    // AJUKAN PEMINJAMAN
    // ==========================================

    public function ajukanPeminjaman(Request $request)
    {
        $request->validate([
            'tgl_kembali_plan' => 'required|date|after:today',
            'alat_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();

        try {

            // Membuat data utama peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tgl_pinjam' => now(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan',
            ]);

            // Membuat detail alat yang dipinjam
            foreach ($request->alat_id as $index => $alatId) {

                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alatId,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('peminjam.riwayat')
                ->with(
                    'success',
                    'Pengajuan peminjaman berhasil dikirim.'
                );

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }


    // ==========================================
    // DATA PEMINJAMAN MILIK PEMINJAM
    // ==========================================

    public function indexPeminjaman()
    {
        $peminjamans = Peminjaman::with([
            'detailPinjams.alat'
        ])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view(
            'peminjam.riwayat',
            compact('peminjamans')
        );
    }


    // ==========================================
    // PENGEMBALIAN MILIK PEMINJAM
    // ==========================================

    public function indexPengembalian()
    {
        $pengembalians = Pengembalian::with('peminjaman')
            ->whereHas('peminjaman', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view(
            'peminjam.pengembalian.index',
            compact('pengembalians')
        );
    }


    // ==========================================
    // RIWAYAT PEMINJAMAN
    // ==========================================

    public function riwayatPeminjaman()
    {
        return $this->indexPeminjaman();
    }
}