@extends('layouts.app')

@section('title', 'Pemantauan Pengembalian - Dashboard Petugas')

@section('header-title', 'Pemantauan & Proses Pengembalian Alat')

@section('content')

    {{-- Pesan sukses --}}
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


    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">

        {{-- Header --}}
        <div class="p-5 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-3">

            <div>
                <h3 class="text-lg font-bold text-gray-800">
                    Daftar Peminjaman Aktif
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Peminjaman yang belum dikembalikan oleh peminjam.
                </p>
            </div>


            {{-- Search --}}
            <form
                action="{{ route('petugas.pengembalian.index') }}"
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
                        href="{{ route('petugas.pengembalian.index') }}"
                        class="ml-2 bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-2 text-sm rounded-lg flex items-center transition"
                    >
                        Reset
                    </a>
                @endif

            </form>

        </div>


        {{-- Tabel --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">

                        <th class="py-3 px-4 border-b">
                            Peminjam
                        </th>

                        <th class="py-3 px-4 border-b">
                            Tgl Pinjam
                        </th>

                        <th class="py-3 px-4 border-b">
                            Rencana Kembali
                        </th>

                        <th class="py-3 px-4 border-b">
                            Status
                        </th>

                        <th class="py-3 px-4 border-b">
                            Detail Alat
                        </th>

                        <th class="py-3 px-4 border-b text-center">
                            Aksi Pengembalian
                        </th>

                    </tr>
                </thead>


                <tbody class="text-gray-700 text-sm">

                    @forelse($peminjamans as $item)

                        @php
                            $isTelat = false;

                            if ($item->tgl_kembali_plan) {
                                $isTelat =
                                    $item->status === 'telat' ||
                                    (
                                        $item->status === 'dipinjam' &&
                                        now()->startOfDay()->gt(
                                            \Carbon\Carbon::parse($item->tgl_kembali_plan)->startOfDay()
                                        )
                                    );
                            }
                        @endphp


                        <tr class="hover:bg-gray-50 transition align-top">

                            {{-- Peminjam --}}
                            <td class="py-3 px-4 border-b font-medium text-gray-900">

                                {{ $item->user->name ?? 'User Dihapus' }}

                            </td>


                            {{-- Tanggal pinjam --}}
                            <td class="py-3 px-4 border-b">

                                {{ $item->tgl_pinjam
                                    ? \Carbon\Carbon::parse($item->tgl_pinjam)->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            {{-- Rencana kembali --}}
                            <td class="py-3 px-4 border-b">

                                {{ $item->tgl_kembali_plan
                                    ? \Carbon\Carbon::parse($item->tgl_kembali_plan)->format('d-m-Y')
                                    : '-'
                                }}

                            </td>


                            {{-- Status --}}
                            <td class="py-3 px-4 border-b">

                                @if($isTelat)

                                    <span class="px-2.5 py-1 rounded text-xs font-semibold bg-red-100 text-red-700">
                                        Telat
                                    </span>

                                @else

                                    <span class="px-2.5 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700">
                                        Dipinjam
                                    </span>

                                @endif

                            </td>


                            {{-- Detail alat --}}
                            <td class="py-3 px-4 border-b">

                                @if($item->detailPinjams->count() > 0)

                                    <ul class="list-disc list-inside space-y-1 text-xs">

                                        @foreach($item->detailPinjams as $detail)

                                            <li>

                                                <span class="font-semibold">
                                                    {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                                </span>

                                                <span class="text-gray-500">
                                                    (Jumlah: {{ $detail->jumlah }})
                                                </span>

                                            </li>

                                        @endforeach

                                    </ul>

                                @else

                                    <span class="text-gray-400">
                                        Tidak ada detail alat.
                                    </span>

                                @endif

                            </td>


                            {{-- Aksi pengembalian --}}
                            <td class="py-3 px-4 border-b text-center">

                                <form
                                    action="{{ route('petugas.pengembalian.proses', $item->id) }}"
                                    method="POST"
                                    class="inline-block bg-gray-50 p-3 rounded border border-gray-200 text-left space-y-2 min-w-[180px]"
                                >

                                    @csrf


                                    {{-- Kondisi --}}
                                    <div>

                                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                                            Kondisi Kembali:
                                        </label>

                                        <select
                                            name="kondisi_kembali"
                                            required
                                            class="w-full text-xs border border-gray-300 rounded px-2 py-1 focus:ring-emerald-500 focus:border-emerald-500"
                                        >

                                            <option value="Baik">
                                                Baik
                                            </option>

                                            <option value="Rusak Ringan">
                                                Rusak Ringan
                                            </option>

                                            <option value="Rusak Berat">
                                                Rusak Berat
                                            </option>

                                        </select>

                                    </div>


                                    {{-- Denda --}}
                                    <div>

                                        <label class="block text-xs font-semibold text-gray-600 mb-1">
                                            Denda (Rp):
                                        </label>

                                        <input
                                            type="number"
                                            name="denda"
                                            value="0"
                                            min="0"
                                            placeholder="0"
                                            class="w-full text-xs border border-gray-300 rounded px-2 py-1 focus:ring-emerald-500 focus:border-emerald-500"
                                        >

                                    </div>


                                    {{-- Tombol --}}
                                    <button
                                        type="submit"
                                        onclick="return confirm('Proses pengembalian alat ini?')"
                                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded text-xs font-semibold transition shadow-sm"
                                    >
                                        Terima Pengembalian
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="py-10 text-center text-gray-500"
                            >

                                <div class="text-sm">
                                    Tidak ada peminjaman yang sedang aktif.
                                </div>

                                @if(request('search'))

                                    <div class="text-xs text-gray-400 mt-1">
                                        Coba gunakan kata kunci pencarian lain.
                                    </div>

                                @endif

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