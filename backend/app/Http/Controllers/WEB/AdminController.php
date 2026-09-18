<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use App\Models\User;
use App\Models\LogAktivitas;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // =========================================================
    // DASHBOARD ADMIN
    // =========================================================

    // Menampilkan Dashboard Admin & Log Aktivitas
    public function index()
    {
        $logs = LogAktivitas::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('logs'));
    }


    // =========================================================
    // CRUD ALAT
    // =========================================================

    // 1. Menampilkan daftar alat
    public function indexAlat(Request $request)
    {
        $search = $request->input('search');

        $alats = Alat::with('kategori')
            ->when($search, function ($query, $search) {
                return $query->where('nama_alat', 'like', "%{$search}%")
                    ->orWhere('status_kondisi', 'like', "%{$search}%")
                    ->orWhereHas('kategori', function ($q) use ($search) {
                        $q->where('nama_kategori', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.alat.index', compact('alats', 'search'));
    }

    // 2. Menampilkan form tambah alat
    public function createAlat()
    {
        $kategoris = Kategori::all();

        return view('admin.alat.create', compact('kategoris'));
    }

    // 3. Menyimpan alat baru
    public function storeAlat(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        // Handle upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('storage/alat'), $filename);

            $data['gambar'] = 'storage/alat/' . $filename;
        }

        $alat = Alat::create($data);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambahkan alat baru: ' . $alat->nama_alat,
        ]);

        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Data alat berhasil ditambahkan.');
    }

    // 4. Menampilkan form edit alat
    public function editAlat($id)
    {
        $alat = Alat::findOrFail($id);
        $kategoris = Kategori::all();

        return view('admin.alat.edit', compact('alat', 'kategoris'));
    }

    // 5. Memperbarui data alat
    public function updateAlat(Request $request, $id)
    {
        $alat = Alat::findOrFail($id);

        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'stok' => 'required|integer|min:0',
            'status_kondisi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();

        // Handle update gambar jika ada file baru
        if ($request->hasFile('gambar')) {

            // Hapus gambar lama jika ada
            if ($alat->gambar && file_exists(public_path($alat->gambar))) {
                unlink(public_path($alat->gambar));
            }

            $file = $request->file('gambar');

            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('storage/alat'), $filename);

            $data['gambar'] = 'storage/alat/' . $filename;
        }

        $alat->update($data);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Memperbarui alat: ' . $alat->nama_alat,
        ]);

        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Data alat berhasil diperbarui.');
    }

    // 6. Menghapus data alat
    public function destroyAlat($id)
    {
        $alat = Alat::findOrFail($id);

        $namaAlat = $alat->nama_alat;

        // Hapus file gambar fisik jika ada
        if ($alat->gambar && file_exists(public_path($alat->gambar))) {
            unlink(public_path($alat->gambar));
        }

        $alat->delete();

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus alat: ' . $namaAlat,
        ]);

        return redirect()
            ->route('admin.alat.index')
            ->with('success', 'Data alat berhasil dihapus.');
    }


    // =========================================================
    // CRUD USER
    // =========================================================

    // 1. Menampilkan daftar user
    public function indexUser(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.user.index', compact('users', 'search'));
    }


    // 2. Menampilkan form tambah user
    public function createUser()
    {
        return view('admin.user.create');
    }


    // 3. Menyimpan user baru
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,petugas,peminjam',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ];

        if ($request->hasFile('foto')) {
            $data['foto_profile'] = $request->file('foto')->store('foto-profil', 'public');
        }

        User::create($data);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil ditambahkan.');
    }


    // 4. Menampilkan form edit user
    public function editUser($id)
    {
        $user = User::findOrFail($id);

        return view('admin.user.edit', compact('user'));
    }


    // 5. Memperbarui data user
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,petugas,peminjam',
            'no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'no_hp' => $request->no_hp,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Jika upload foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama dari storage
            if ($user->foto_profile && Storage::disk('public')->exists($user->foto_profile)) {
                Storage::disk('public')->delete($user->foto_profile);
            }

            // Simpan foto baru
            $data['foto_profile'] = $request->file('foto')->store('foto-profil', 'public');
        }

        $user->update($data);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Memperbarui user: ' . $user->name,
        ]);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }


    // 6. Menghapus user
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);

        $namaUser = $user->name;

        // Hapus foto profile jika ada
        if ($user->foto && file_exists(public_path($user->foto))) {
            unlink(public_path($user->foto));
        }

        $user->delete();

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus user: ' . $namaUser,
        ]);

        return redirect()
            ->route('admin.user.index')
            ->with('success', 'User berhasil dihapus.');
    }


    // =========================================================
    // CRUD KATEGORI
    // =========================================================

    // 1. Menampilkan daftar kategori
    public function indexKategori(Request $request)
    {
        $search = $request->input('search');

        $kategoris = Kategori::when($search, function ($query, $search) {
            return $query->where(
                'nama_kategori',
                'like',
                "%{$search}%"
            );
        })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view(
            'admin.kategori.index',
            compact('kategoris', 'search')
        );
    }

    // 2. Menampilkan form tambah kategori
    public function createKategori()
    {
        return view('admin.kategori.create');
    }

    // 3. Menyimpan kategori baru
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menambahkan kategori baru: ' . $kategori->nama_kategori,
        ]);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    // 4. Menampilkan form edit kategori
    public function editKategori($id)
    {
        $kategori = Kategori::findOrFail($id);

        return view('admin.kategori.edit', compact('kategori'));
    }

    // 5. Memperbarui kategori
    public function updateKategori(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id,
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Memperbarui kategori: ' . $kategori->nama_kategori,
        ]);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    // 6. Menghapus kategori
    public function destroyKategori($id)
    {
        $kategori = Kategori::findOrFail($id);

        // Cek apakah kategori masih digunakan oleh alat
        if ($kategori->alats()->count() > 0) {
            return redirect()
                ->route('admin.kategori.index')
                ->with(
                    'error',
                    'Kategori tidak dapat dihapus karena masih digunakan oleh data alat.'
                );
        }

        $namaKategori = $kategori->nama_kategori;

        $kategori->delete();

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Menghapus kategori: ' . $namaKategori,
        ]);

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    // =========================================================
    // CRUD PEMINJAMAN
    // =========================================================

    // 1. Menampilkan daftar peminjaman
    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with(['user', 'detailPinjams.alat'])
            ->when($search, function ($query, $search) {
                return $query->where('status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.peminjaman.index', compact('peminjamans', 'search'));
    }

    // 2. Menampilkan form tambah peminjaman
    public function createPeminjaman()
    {
        $users = User::where('role', 'peminjam')->get(); // Atau ambil semua user jika bebas
        $alats = Alat::where('stok', '>', 0)->get();

        return view('admin.peminjaman.create', compact('users', 'alats'));
    }

    // 3. Menyimpan data peminjaman baru
    public function storePeminjaman(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_plan' => 'required|date|after_or_equal:tgl_pinjam',
            'alat_id' => 'required|array',
            'alat_id.*' => 'exists:alat,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            // Buat transaksi utama peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => $request->user_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan', // Status awal
            ]);

            // Simpan detail alat yang dipinjam
            foreach ($request->alat_id as $index => $alatId) {
                $jumlahPinjam = $request->jumlah[$index];

                $alat = Alat::findOrFail($alatId);

                // Validasi stok
                if ($alat->stok < $jumlahPinjam) {
                    throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi.");
                }

                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alatId,
                    'jumlah' => $jumlahPinjam,
                ]);

                // Kurangi stok alat saat peminjaman diajukan
                $alat->decrement('stok', $jumlahPinjam);
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Mengajukan peminjaman baru untuk user ID: ' . $peminjaman->user_id,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // 4. Menampilkan form edit peminjaman
    public function editPeminjaman($id)
    {
        $peminjaman = Peminjaman::with('detailPinjams.alat')->findOrFail($id);
        $users = User::where('role', 'peminjam')->get();
        $alats = Alat::all();

        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'alats'));
    }

    // 5. Memperbarui data peminjaman
    public function updatePeminjaman(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tgl_pinjam' => 'required|date',
            'tgl_kembali_plan' => 'required|date|after_or_equal:tgl_pinjam',
            'alat_id' => 'required|array',
            'alat_id.*' => 'exists:alats,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
        ]);

        $peminjaman = Peminjaman::with('detailPinjams')->findOrFail($id);

        DB::beginTransaction();

        try {
            // Kembalikan dulu stok alat lama sebelum menerapkan detail baru
            foreach ($peminjaman->detailPinjams as $detailLama) {
                $alatLama = Alat::find($detailLama->alat_id);

                if ($alatLama) {
                    $alatLama->increment('stok', $detailLama->jumlah);
                }
            }

            // Hapus detail lama
            $peminjaman->detailPinjams()->delete();

            // Update data utama peminjaman
            $peminjaman->update([
                'user_id' => $request->user_id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
            ]);

            // Simpan detail alat yang baru
            foreach ($request->alat_id as $index => $alatId) {
                $jumlahPinjam = $request->jumlah[$index];

                $alat = Alat::findOrFail($alatId);

                if ($alat->stok < $jumlahPinjam) {
                    throw new \Exception("Stok alat '{$alat->nama_alat}' tidak mencukupi.");
                }

                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alatId,
                    'jumlah' => $jumlahPinjam,
                ]);

                $alat->decrement('stok', $jumlahPinjam);
            }

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Memperbarui data peminjaman ID: ' . $peminjaman->id,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // 6. Menghapus data peminjaman
    public function destroyPeminjaman($id)
    {
        $peminjaman = Peminjaman::with('detailPinjams')->findOrFail($id);

        DB::beginTransaction();

        try {
            // Kembalikan stok alat jika peminjaman masih berjalan (belum dikembalikan)
            if ($peminjaman->status !== 'dikembalikan') {
                foreach ($peminjaman->detailPinjams as $detail) {
                    $alat = Alat::find($detail->alat_id);

                    if ($alat) {
                        $alat->increment('stok', $detail->jumlah);
                    }
                }
            }

            $peminjaman->detailPinjams()->delete();
            $peminjaman->delete();

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id' => auth()->id(),
                'aktivitas' => 'Menghapus data peminjaman ID: ' . $id,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    // 7. Update status peminjaman (dipakai dropdown status di halaman index)
    public function updateStatusPeminjaman(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,dipinjam,selesai,telat',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        $peminjaman->update([
            'status' => $request->status,
        ]);

        // Catat log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Mengubah status peminjaman ID ' . $peminjaman->id . ' menjadi ' . $request->status,
        ]);

        return redirect()
            ->route('admin.peminjaman.index')
            ->with('success', 'Status peminjaman berhasil diperbarui.');
    }

        // =========================================================
    // CRUD PENGEMBALIAN
    // =========================================================

    // 1. Menampilkan daftar pengembalian
    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search');

        $pengembalians = Pengembalian::with(['peminjaman.user', 'petugas'])
            ->when($search, function ($query, $search) {
                return $query->where('kondisi_kembali', 'like', "%{$search}%")
                    ->orWhereHas('peminjaman.user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.pengembalian.index',
            compact('pengembalians', 'search')
        );
    }


    // 2. Menampilkan form tambah/proses pengembalian
    public function createPengembalian()
    {
        // Ambil data peminjaman yang masih aktif
        $peminjamans = Peminjaman::with('user')
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->get();

        return view(
            'admin.pengembalian.create',
            compact('peminjamans')
        );
    }


    // 3. Menyimpan data pengembalian baru dengan denda otomatis
    public function storePengembalian(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamans,id',
            'tgl_kembali' => 'required|date',
            'kondisi_kembali' => 'required|string|max:255',
            'denda_tambahan' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with('detailPinjams.alat')
                ->findOrFail($request->peminjaman_id);

            // Hitung keterlambatan otomatis
            $tglPlan = Carbon::parse($peminjaman->tgl_kembali_plan);
            $tglAktual = Carbon::parse($request->tgl_kembali);

            $dendaOtomatis = 0;
            $tarifDendaPerHari = 5000;

            if ($tglAktual->greaterThan($tglPlan)) {
                $selisihHari = $tglPlan->diffInDays($tglAktual);
                $dendaOtomatis = $selisihHari * $tarifDendaPerHari;
            }

            // Total denda
            $dendaTambahan = $request->denda_tambahan ?? 0;
            $totalDenda = $dendaOtomatis + $dendaTambahan;

            // Simpan data pengembalian
            Pengembalian::create([
                'peminjaman_id' => $request->peminjaman_id,
                'tgl_kembali' => $request->tgl_kembali,
                'kondisi_kembali' => $request->kondisi_kembali,
                'denda' => $totalDenda,
                'petugas_id' => auth()->id(),
            ]);

            // Ubah status peminjaman menjadi selesai
            $peminjaman->update([
                'status' => 'selesai'
            ]);

            // Kembalikan stok alat
            foreach ($peminjaman->detailPinjams as $detail) {
                $detail->alat->increment('stok', $detail->jumlah);
            }

            DB::commit();

            return redirect()
                ->route('admin.pengembalian.index')
                ->with(
                    'success',
                    'Pengembalian berhasil diproses. Denda otomatis terhitung: Rp ' .
                    number_format($totalDenda, 0, ',', '.')
                );

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


    // 4. Menampilkan form edit pengembalian
    public function editPengembalian($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);

        // Ambil data peminjaman
        $peminjamans = Peminjaman::with('user')
            ->whereIn('status', ['dipinjam', 'disetujui', 'selesai'])
            ->get();

        // Ambil semua user yang berperan sebagai petugas
        $petugas = User::where('role', 'petugas')->get();

        return view(
            'admin.pengembalian.edit',
            compact(
                'pengembalian',
                'peminjamans',
                'petugas'
            )
        );
    }


    // 5. Memperbarui data pengembalian
    public function updatePengembalian(Request $request, $id)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tgl_kembali' => 'required|date',
            'kondisi_kembali' => 'required|string|max:255',
            'denda' => 'required|integer|min:0',
            'petugas_id' => 'required|exists:users,id',
        ]);

        $pengembalian = Pengembalian::findOrFail($id);

        $pengembalian->update([
            'peminjaman_id' => $request->peminjaman_id,
            'tgl_kembali' => $request->tgl_kembali,
            'kondisi_kembali' => $request->kondisi_kembali,
            'denda' => $request->denda,
            'petugas_id' => $request->petugas_id,
        ]);

        return redirect()
            ->route('admin.pengembalian.index')
            ->with(
                'success',
                'Data pengembalian berhasil diperbarui.'
            );
    }


    // 6. Menghapus data pengembalian
    public function destroyPengembalian($id)
    {
        $pengembalian = Pengembalian::with(
            'peminjaman.detailPinjams.alat'
        )->findOrFail($id);

        DB::beginTransaction();

        try {
            $peminjaman = $pengembalian->peminjaman;

            // Jika data pengembalian dihapus,
            // status peminjaman dikembalikan menjadi dipinjam
            // dan stok alat dikurangi kembali
            if ($peminjaman) {
                $peminjaman->update([
                    'status' => 'dipinjam'
                ]);

                foreach ($peminjaman->detailPinjams as $detail) {
                    $detail->alat->decrement(
                        'stok',
                        $detail->jumlah
                    );
                }
            }

            $pengembalian->delete();

            DB::commit();

            return redirect()
                ->route('admin.pengembalian.index')
                ->with(
                    'success',
                    'Data pengembalian berhasil dihapus.'
                );

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->with('error', $e->getMessage());
        }
    }

        public function profile()
    {
        $user = auth()->user();

        return view('admin.profile', compact('user'));
    }


    // MENGEDIT PROFILE //
        public function editProfile()
    {
        $user = auth()->user();

        return view('admin.profile-edit', compact('user'));
    }

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

            // Simpan foto baru terlebih dahulu
            $path = $request->file('foto')->store('profile', 'public');

            // Hapus foto lama jika ada
            if ($user->foto && \Storage::disk('public')->exists($user->foto)) {
                \Storage::disk('public')->delete($user->foto);
            }

            $data['foto'] = $path;
        }

        $user->update($data);

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}