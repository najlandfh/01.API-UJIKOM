@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Peminjaman')

@section('header-title', 'Dashboard Admin')

@section('content')

{{-- Alert Selamat Datang --}}
<div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-lg shadow-sm">
    Selamat datang,
    <strong class="font-semibold">{{ auth()->user()->name }}</strong>!
    Anda login sebagai hak akses
    <span class="uppercase font-bold text-emerald-900">
        {{ auth()->user()->role }}
    </span>.
</div>

{{-- Statistik (setiap kotak bisa diklik) --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">

    {{-- Total User --}}
    <a href="{{ route('admin.user.index') }}"
        class="block bg-blue-500 text-white rounded-xl shadow-sm p-5 transition duration-200 hover:brightness-110 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total User</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalUser }}
                </h2>
            </div>

            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>
    </a>

    {{-- Total Kategori --}}
    <a href="{{ route('admin.kategori.index') }}"
        class="block bg-purple-500 text-white rounded-xl shadow-sm p-5 transition duration-200 hover:brightness-110 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total Kategori</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalKategori }}
                </h2>
            </div>

            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </div>
        </div>
    </a>

    {{-- Total Alat --}}
    <a href="{{ route('admin.alat.index') }}"
        class="block bg-orange-500 text-white rounded-xl shadow-sm p-5 transition duration-200 hover:brightness-110 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total Alat</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalAlat }}
                </h2>
            </div>

            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a3 3 0 006 0M9 5h6" />
                </svg>
            </div>
        </div>
    </a>

    {{-- Total Peminjaman --}}
    <a href="{{ route('admin.peminjaman.index') }}"
        class="block bg-emerald-500 text-white rounded-xl shadow-sm p-5 transition duration-200 hover:brightness-110 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total Peminjaman</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalPeminjaman }}
                </h2>
            </div>

            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v12m6-6H6" />
                </svg>
            </div>
        </div>
    </a>

    {{-- Total Pengembalian --}}
    <a href="{{ route('admin.pengembalian.index') }}"
        class="block bg-red-500 text-white rounded-xl shadow-sm p-5 transition duration-200 hover:brightness-110 hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium opacity-90">Total Pengembalian</p>
                <h2 class="text-3xl font-bold mt-2">
                    {{ $totalPengembalian }}
                </h2>
            </div>

            <div class="bg-white/20 rounded-full p-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-7 h-7"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 15l3 3L21 9M5 12h.01" />
                </svg>
            </div>
        </div>
    </a>

</div>

{{-- Log Aktivitas Terbaru --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">

    <div class="p-5 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-800">
                    Log Aktivitas Terbaru
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Menampilkan aktivitas terbaru dalam sistem.
                </p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                    <th class="py-3 px-4 border-b">Waktu</th>
                    <th class="py-3 px-4 border-b">User</th>
                    <th class="py-3 px-4 border-b">Aktivitas</th>
                </tr>
            </thead>

            <tbody class="text-gray-700 text-sm">

                @forelse($logs as $log)

                    <tr class="hover:bg-gray-50 transition">

                        <td class="py-3 px-4 border-b whitespace-nowrap">
                            {{ $log->created_at->format('d M Y, H:i') }}
                        </td>

                        <td class="py-3 px-4 border-b font-medium text-gray-900">
                            {{ $log->user->name ?? 'Sistem' }}
                        </td>

                        <td class="py-3 px-4 border-b">
                            {{ $log->aktivitas }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3"
                            class="py-6 text-center text-gray-500">
                            Belum ada log aktivitas.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

</div>

@endsection