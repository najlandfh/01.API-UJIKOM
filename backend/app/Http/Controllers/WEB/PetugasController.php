<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    // ============================================================
    // PEMINJAMAN & PERSETUJUAN
    // ============================================================

    /**
     * Menampilkan daftar pengajuan peminjaman
     * yang menunggu persetujuan petugas.
     */
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with([
            'user',
            'detailPinjams.alat'
        ])
            ->where('status', 'diajukan')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'petugas.peminjaman.index',
            compact('peminjamans', 'search')
        );
    }


    /**
     * Menyetujui pengajuan peminjaman.
     *
     * Status:
     * diajukan -> dipinjam
     *
     * Jika disetujui, stok alat akan dikurangi
     * sesuai jumlah yang dipinjam.
     */
    public function setujuiPeminjaman($id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with('detailPinjams')
                ->lockForUpdate()
                ->findOrFail($id);

            // Pastikan pengajuan masih menunggu persetujuan
            if ($peminjaman->status !== 'diajukan') {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Peminjaman ini sudah diproses sebelumnya.'
                    );
            }

            // Pastikan detail peminjaman tersedia
            if ($peminjaman->detailPinjams->isEmpty()) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Detail alat pada peminjaman ini tidak ditemukan.'
                    );
            }

            // Cek seluruh stok terlebih dahulu
            foreach ($peminjaman->detailPinjams as $detail) {
                $alat = Alat::lockForUpdate()
                    ->find($detail->alat_id);

                if (!$alat) {
                    DB::rollBack();

                    return redirect()
                        ->back()
                        ->with(
                            'error',
                            'Data alat tidak ditemukan.'
                        );
                }

                if ($alat->stok < $detail->jumlah) {
                    DB::rollBack();

                    return redirect()
                        ->back()
                        ->with(
                            'error',
                            'Stok alat "' .
                            $alat->nama_alat .
                            '" tidak mencukupi.'
                        );
                }
            }

            // Kurangi stok alat
            foreach ($peminjaman->detailPinjams as $detail) {
                $alat = Alat::lockForUpdate()
                    ->findOrFail($detail->alat_id);

                $alat->stok -= $detail->jumlah;
                $alat->save();
            }

            // Ubah status peminjaman
            $peminjaman->update([
                'status' => 'dipinjam'
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Peminjaman berhasil disetujui dan stok alat berhasil dikurangi.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menyetujui peminjaman.'
                );
        }
    }


    /**
     * Menolak pengajuan peminjaman.
     *
     * Status:
     * diajukan -> ditolak
     *
     * Data tidak dihapus agar tetap menjadi
     * riwayat dan dapat ditampilkan pada laporan.
     */
    public function tolakPeminjaman($id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::lockForUpdate()
                ->findOrFail($id);

            // Pastikan status masih diajukan
            if ($peminjaman->status !== 'diajukan') {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Peminjaman ini sudah diproses sebelumnya.'
                    );
            }

            // Ubah status menjadi ditolak
            $peminjaman->update([
                'status' => 'ditolak'
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Pengajuan peminjaman berhasil ditolak.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat menolak peminjaman.'
                );
        }
    }


    // ============================================================
    // PENGEMBALIAN
    // ============================================================

    /**
     * Menampilkan daftar peminjaman yang belum dikembalikan.
     */
    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with([
            'user',
            'detailPinjams.alat'
        ])
            ->whereIn('status', ['dipinjam', 'telat'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'petugas.pengembalian.index',
            compact('peminjamans', 'search')
        );
    }


    /**
     * Memproses pengembalian alat.
     */
    public function prosesPengembalian(
        Request $request,
        $peminjamanId
    ) {
        $request->validate([
            'kondisi_kembali' => 'required|string|max:255',
            'denda' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with('detailPinjams')
                ->lockForUpdate()
                ->findOrFail($peminjamanId);

            // Hanya peminjaman dengan status dipinjam
            // atau telat yang boleh dikembalikan
            if (!in_array(
                $peminjaman->status,
                ['dipinjam', 'telat']
            )) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Peminjaman ini sudah tidak dapat diproses karena statusnya sudah berubah.'
                    );
            }

            // Pastikan belum pernah dikembalikan
            $sudahDikembalikan = Pengembalian::where(
                'peminjaman_id',
                $peminjaman->id
            )->exists();

            if ($sudahDikembalikan) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Peminjaman ini sudah memiliki data pengembalian.'
                    );
            }

            // Simpan data pengembalian
            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_kembali' => now(),
                'kondisi_kembali' => $request->kondisi_kembali,
                'denda' => $request->denda ?? 0,
                'petugas_id' => auth()->id(),
            ]);

            // Ubah status menjadi selesai
            $peminjaman->update([
                'status' => 'selesai'
            ]);

            // Kembalikan stok alat
            foreach ($peminjaman->detailPinjams as $detail) {
                $alat = Alat::lockForUpdate()
                    ->findOrFail($detail->alat_id);

                $alat->stok += $detail->jumlah;
                $alat->save();
            }

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Pengembalian berhasil diproses dan stok alat telah dikembalikan.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan saat memproses pengembalian.'
                );
        }
    }


    // ============================================================
    // LAPORAN
    // ============================================================

    /**
     * Menampilkan laporan peminjaman dan pengembalian.
     */
    public function laporan(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');

        $peminjamans = Peminjaman::with([
            'user',
            'detailPinjams.alat',
            'pengembalian'
        ])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($dariTanggal, function ($query, $dariTanggal) {
                $query->whereDate(
                    'tgl_pinjam',
                    '>=',
                    $dariTanggal
                );
            })
            ->when($sampaiTanggal, function ($query, $sampaiTanggal) {
                $query->whereDate(
                    'tgl_pinjam',
                    '<=',
                    $sampaiTanggal
                );
            })
            ->latest()
            ->get();

        return view(
            'petugas.laporan.index',
            compact(
                'peminjamans',
                'search',
                'status',
                'dariTanggal',
                'sampaiTanggal'
            )
        );
    }


    /**
     * Mencetak laporan.
     */
    public function cetakLaporan(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $dariTanggal = $request->input('dari_tanggal');
        $sampaiTanggal = $request->input('sampai_tanggal');

        $laporan = Peminjaman::with([
            'user',
            'detailPinjams.alat',
            'pengembalian'
        ])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->when($dariTanggal, function ($query, $dariTanggal) {
                $query->whereDate(
                    'tgl_pinjam',
                    '>=',
                    $dariTanggal
                );
            })
            ->when($sampaiTanggal, function ($query, $sampaiTanggal) {
                $query->whereDate(
                    'tgl_pinjam',
                    '<=',
                    $sampaiTanggal
                );
            })
            ->latest()
            ->get();

        return view(
            'petugas.laporan.cetak',
            compact(
                'laporan',
                'status',
                'dariTanggal',
                'sampaiTanggal'
            )
        );
    }


    // ============================================================
    // PROFIL PETUGAS
    // ============================================================

    /**
     * Menampilkan profil petugas.
     */
    public function profile()
    {
        $user = auth()->user();

        return view(
            'petugas.profile',
            compact('user')
        );
    }


    /**
     * Menampilkan halaman edit profil.
     */
    public function editProfile()
    {
        $user = auth()->user();

        return view(
            'petugas.profile-edit',
            compact('user')
        );
    }


    /**
     * Memperbarui profil petugas.
     */
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

        // Jika user memilih foto baru
        if ($request->hasFile('foto')) {

            // Simpan foto baru
            $path = $request
                ->file('foto')
                ->store('profile', 'public');

            // Hapus foto lama jika ada
            if (
                $user->foto &&
                Storage::disk('public')->exists($user->foto)
            ) {
                Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $path;
        }

        $user->update($data);

        return redirect()
            ->route('petugas.profile')
            ->with(
                'success',
                'Profil berhasil diperbarui.'
            );
    }
}