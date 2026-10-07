@extends('layouts.app')

@section('title', 'Log Aktivitas - Sistem Peminjaman')

@section('header-title', 'Log Aktivitas')

@section('content')

{{-- Panel Log Aktivitas --}}

<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">

{{-- Header Panel --}}
<div class="p-5 border-b border-gray-200 bg-gray-50">
    <div>
        <h3 class="text-lg font-bold text-gray-800">
            Log Aktivitas
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            Menampilkan seluruh aktivitas yang terjadi dalam sistem.
        </p>
    </div>
</div>

{{-- Tabel --}}
<div class="overflow-x-auto">

    <table class="w-full text-left border-collapse">

        <thead>
            <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">

                <th class="py-3 px-4 border-b">
                    No
                </th>

                <th class="py-3 px-4 border-b">
                    Waktu
                </th>

                <th class="py-3 px-4 border-b">
                    User
                </th>

                <th class="py-3 px-4 border-b">
                    Aktivitas
                </th>

            </tr>
        </thead>

        <tbody class="text-gray-700 text-sm">

            @forelse($logs as $log)

                <tr class="hover:bg-gray-50 transition">

                    {{-- Nomor --}}
                    <td class="py-3 px-4 border-b whitespace-nowrap">
                        {{ $logs->firstItem() + $loop->index }}
                    </td>

                    {{-- Waktu --}}
                    <td class="py-3 px-4 border-b whitespace-nowrap">
                        {{ $log->created_at->format('d M Y, H:i') }}
                    </td>

                    {{-- User --}}
                    <td class="py-3 px-4 border-b font-medium text-gray-900">
                        {{ $log->user->name ?? 'Sistem' }}
                    </td>

                    {{-- Aktivitas --}}
                    <td class="py-3 px-4 border-b">
                        {{ $log->aktivitas }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4"
                        class="py-8 text-center text-gray-500">
                        Belum ada log aktivitas.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

{{-- Pagination --}}
@if($logs->hasPages())

    <div class="px-5 py-4 border-t border-gray-200 bg-gray-50">

        <div class="flex items-center justify-between">

            {{-- Informasi Data --}}
            <p class="text-sm text-gray-500">
                Menampilkan
                <span class="font-medium text-gray-700">
                    {{ $logs->firstItem() }}
                </span>
                -
                <span class="font-medium text-gray-700">
                    {{ $logs->lastItem() }}
                </span>
                dari
                <span class="font-medium text-gray-700">
                    {{ $logs->total() }}
                </span>
                log
            </p>

            {{-- Tombol Pagination --}}
            <div class="flex items-center gap-1">

                {{-- Previous --}}
                @if($logs->onFirstPage())

                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                        Previous
                    </span>

                @else

                    <a href="{{ $logs->previousPageUrl() }}"
                       class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                        Previous
                    </a>

                @endif


                {{-- Nomor Halaman --}}
                @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)

                    @if($page == $logs->currentPage())

                        <span class="px-3 py-2 text-sm font-semibold text-white bg-emerald-500 border border-emerald-500 rounded-lg">
                            {{ $page }}
                        </span>

                    @else

                        <a href="{{ $url }}"
                           class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                            {{ $page }}
                        </a>

                    @endif

                @endforeach


                {{-- Next --}}
                @if($logs->hasMorePages())

                    <a href="{{ $logs->nextPageUrl() }}"
                       class="px-3 py-2 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition">
                        Next
                    </a>

                @else

                    <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed">
                        Next
                    </span>

                @endif

            </div>

        </div>

    </div>

@endif

</div>

@endsection
