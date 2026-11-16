
@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')
@section('header-title', 'Riwayat Peminjaman')

@section('content')

    {{-- JUDUL DAN TOMBOL AJUKAN --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Riwayat Peminjaman
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Daftar pengajuan dan peminjaman alat Anda.
            </p>
        </div>

        <a
            href="{{ route('peminjam.alat.index') }}"
            class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-lg shadow-sm transition"
        >
            + Ajukan Peminjaman
        </a>

    </div>


    {{-- PESAN SUKSES --}}
    @if(session('success'))
        <div class="mb-5 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif


    {{-- PESAN ERROR --}}
    @if(session('error'))
        <div class="mb-5 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif


    {{-- JIKA BELUM ADA RIWAYAT --}}
    @if($peminjamans->isEmpty())

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 min-h-[270px] flex flex-col items-center justify-center text-center p-8">

            <div class="text-5xl mb-4">
                📋
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-2">
                Belum Ada Riwayat Peminjaman
            </h3>

            <p class="text-gray-500 text-sm mb-6">
                Anda belum memiliki pengajuan atau peminjaman alat.
            </p>

            <a
                href="{{ route('peminjam.alat.index') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition shadow-sm"
            >
                Lihat Katalog Alat
            </a>

        </div>


    {{-- JIKA SUDAH ADA RIWAYAT --}}
    @else

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- HEADER TABEL --}}
            <div class="p-5 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">
                    Daftar Pengajuan & Peminjaman Saya
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Berikut adalah seluruh riwayat peminjaman alat Anda.
                </p>
            </div>


            {{-- TABEL RIWAYAT --}}
            <div class="overflow-x-auto">

                <table class="w-full text-left border-collapse">

                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">

                            <th class="py-3 px-4 border-b">
                                No
                            </th>

                            <th class="py-3 px-4 border-b">
                                Tanggal Pinjam
                            </th>

                            <th class="py-3 px-4 border-b">
                                Rencana Kembali
                            </th>

                            <th class="py-3 px-4 border-b">
                                Daftar Alat
                            </th>

                            <th class="py-3 px-4 border-b">
                                Status Peminjaman
                            </th>

                        </tr>
                    </thead>


                    <tbody class="text-gray-700 text-sm">

                        @foreach($peminjamans as $index => $item)

                            @php
                                $status = strtolower($item->status ?? '');
                            @endphp

                            <tr class="hover:bg-gray-50 transition">

                                {{-- NOMOR --}}
                                <td class="py-3 px-4 border-b">
                                    {{ $index + 1 }}
                                </td>


                                {{-- TANGGAL PINJAM --}}
                                <td class="py-3 px-4 border-b whitespace-nowrap">
                                    {{ $item->tgl_pinjam
                                        ? \Carbon\Carbon::parse($item->tgl_pinjam)->format('d/m/Y')
                                        : '-' }}
                                </td>


                                {{-- RENCANA KEMBALI --}}
                                <td class="py-3 px-4 border-b whitespace-nowrap">
                                    {{ $item->tgl_kembali_plan
                                        ? \Carbon\Carbon::parse($item->tgl_kembali_plan)->format('d/m/Y')
                                        : '-' }}
                                </td>


                                {{-- DAFTAR ALAT --}}
                                <td class="py-3 px-4 border-b">

                                    <ul class="list-disc list-inside space-y-1">

                                        @forelse($item->detailPinjams as $detail)

                                            <li>
                                                {{ $detail->alat->nama_alat ?? 'Alat tidak ditemukan' }}
                                                ({{ $detail->jumlah }} unit)
                                            </li>

                                        @empty

                                            <li class="list-none text-gray-400">
                                                Tidak ada detail alat.
                                            </li>

                                        @endforelse

                                    </ul>

                                </td>


                                {{-- STATUS --}}
                                <td class="py-3 px-4 border-b whitespace-nowrap">

                                    @if($status === 'diajukan')

                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Diajukan
                                        </span>

                                    @elseif(in_array($status, ['disetujui', 'dipinjam']))

                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Sedang Dipinjam
                                        </span>

                                    @elseif(in_array($status, ['dikembalikan', 'selesai']))

                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Selesai
                                        </span>

                                    @elseif($status === 'ditolak')

                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Ditolak
                                        </span>

                                    @else

                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                            {{ ucfirst($item->status ?? 'Tidak diketahui') }}
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif

@endsection
