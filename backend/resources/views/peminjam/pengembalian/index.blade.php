@extends('layouts.app')

@section('title', 'Pengembalian')

@section('header-title', 'Pengembalian Alat')

@section('content')

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg shadow-sm text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">

        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">
                Riwayat Pengembalian Saya
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                Daftar pengembalian alat yang telah tercatat.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse">

                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">

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
                            Status Peminjaman
                        </th>

                    </tr>
                </thead>

                <tbody class="text-gray-700 text-sm">

                    @forelse($pengembalians as $index => $pengembalian)

                        <tr class="hover:bg-gray-50 transition">

                            <td class="py-3 px-4 border-b">
                                {{ $index + 1 }}
                            </td>

                            <td class="py-3 px-4 border-b">
                                {{ $pengembalian->peminjaman->tgl_pinjam ?? '-' }}
                            </td>

                            <td class="py-3 px-4 border-b">
                                {{ $pengembalian->peminjaman->tgl_kembali_plan ?? '-' }}
                            </td>

                            <td class="py-3 px-4 border-b">

                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                    @if($pengembalian->peminjaman->status == 'diajukan')
                                        bg-yellow-100 text-yellow-800
                                    @elseif($pengembalian->peminjaman->status == 'dipinjam')
                                        bg-blue-100 text-blue-800
                                    @elseif($pengembalian->peminjaman->status == 'selesai')
                                        bg-emerald-100 text-emerald-800
                                    @else
                                        bg-red-100 text-red-800
                                    @endif
                                ">
                                    {{ ucfirst($pengembalian->peminjaman->status ?? '-') }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500">
                                Belum ada data pengembalian.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection