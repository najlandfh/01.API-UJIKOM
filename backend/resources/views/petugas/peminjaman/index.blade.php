@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman - Dashboard Petugas')
@section('header-title', 'Daftar Pengajuan Peminjaman Alat')

@section('content')

    {{-- Pesan berhasil --}}
    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error --}}
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('error') }}
        </div>
    @endif


    {{-- Header dan pencarian --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 mb-4">

        <div class="p-5 border-b border-gray-200 bg-gray-50">

            <h3 class="text-lg font-bold text-gray-800 mb-3">
                Menunggu Verifikasi Persetujuan
            </h3>

            <form
                action="{{ route('petugas.peminjaman.index') }}"
                method="GET"
                class="flex w-full md:w-80"
            >

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama peminjam..."
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                >

                <button
                    type="submit"
                    class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 text-sm font-semibold rounded-r-lg transition"
                >
                    Cari
                </button>

                @if(request('search'))
                    <a
                        href="{{ route('petugas.peminjaman.index') }}"
                        class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-sm rounded-lg flex items-center transition"
                    >
                        Reset
                    </a>
                @endif

            </form>

        </div>

    </div>


    {{-- Tabel peminjaman --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                        <th class="py-3 px-4 border-b">Peminjam</th>
                        <th class="py-3 px-4 border-b">Tanggal Pinjam</th>
                        <th class="py-3 px-4 border-b">Rencana Kembali</th>
                        <th class="py-3 px-4 border-b">Detail Alat</th>
                        <th class="py-3 px-4 border-b text-center">Aksi</th>
                    </tr>
                </thead>


                <tbody class="text-gray-700 text-sm">

                    @forelse($peminjamans as $item)

                        <tr class="hover:bg-gray-50 transition align-top">

                            {{-- Nama peminjam --}}
                            <td class="py-3 px-4 border-b font-medium text-gray-900">
                                {{ $item->user->name ?? 'User Dihapus' }}
                            </td>

                            {{-- Tanggal pinjam --}}
                            <td class="py-3 px-4 border-b">
                                {{ $item->tgl_pinjam }}
                            </td>

                            {{-- Rencana kembali --}}
                            <td class="py-3 px-4 border-b">
                                {{ $item->tgl_kembali_plan }}
                            </td>

                            {{-- Detail alat --}}
                            <td class="py-3 px-4 border-b">
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    @foreach($item->detailPinjams as $detail)
                                        <li>
                                            <span class="font-semibold">
                                                {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                            </span>
                                            (Jumlah: {{ $detail->jumlah }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3 px-4 border-b">

                                @if($item->status === 'diajukan')

                                    <div class="flex items-center justify-center gap-2">

                                        {{-- SETUJUI --}}
                                        <form action="{{ route('petugas.peminjaman.setujui', $item->id) }}"
                                              method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition">
                                                Setujui
                                            </button>
                                        </form>

                                        {{-- TOLAK --}}
                                        <form action="{{ route('petugas.peminjaman.tolak', $item->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menolak pengajuan peminjaman ini?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition">
                                                Tolak
                                            </button>
                                        </form>

                                    </div>

                                @else

                                    <div class="text-center">
                                        <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="py-6 text-center text-gray-500">
                                Tidak ada pengajuan peminjaman baru.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if($peminjamans->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $peminjamans->links() }}
            </div>
        @endif

    </div>

@endsection