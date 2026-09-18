@extends('layouts.app')

@section('title', 'Profil Saya')
@section('header-title', 'Profil Saya')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

    {{-- Header --}}
    <div class="bg-gray-900 px-6 py-5 text-white">
        <h2 class="text-xl font-semibold">
            Profil Saya
        </h2>

        <p class="text-sm text-gray-300 mt-1">
            Informasi akun pengguna yang sedang login
        </p>
    </div>

    {{-- Isi --}}
    <div class="p-6">

        {{-- Pesan berhasil --}}
        @if(session('success'))
            <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error --}}
        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- FOTO PROFIL --}}
            <div class="flex flex-col items-center">

                <div class="w-36 h-36 rounded-full overflow-hidden border-4 border-gray-200 bg-gray-100">

                    @if($user->foto)
                        <img
                            src="{{ asset('storage/' . $user->foto) }}"
                            alt="Foto Profil"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-16 h-16"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"
                                />
                            </svg>
                        </div>
                    @endif

                </div>

                <p class="mt-4 text-sm font-semibold text-gray-800">
                    {{ $user->name }}
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    {{ ucfirst($user->role) }}
                </p>

            </div>


            {{-- INFORMASI USER --}}
            <div class="md:col-span-2 space-y-5">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama
                    </label>

                    <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-3 text-gray-700">
                        {{ $user->name }}
                    </div>
                </div>


                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>

                    <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-3 text-gray-700">
                        {{ $user->email }}
                    </div>
                </div>


                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Role
                    </label>

                    <div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full
                        bg-blue-100 text-blue-700 text-sm font-semibold">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <p class="text-xs text-gray-400 mt-2">
                        Role tidak dapat diubah melalui profil.
                    </p>
                </div>


                {{-- No HP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        No. HP
                    </label>

                    <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-3 text-gray-700">
                        {{ $user->no_hp ?? '-' }}
                    </div>
                </div>


                {{-- Alamat --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat
                    </label>

                    <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-3 text-gray-700">
                        {{ $user->alamat ?? '-' }}
                    </div>
                </div>


                {{-- Tombol Edit --}}
                <div class="mt-6 pt-5 border-t border-gray-200 flex justify-end">

                    <a
                        href="{{ route('admin.profile.edit') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white
                        font-medium px-5 py-2.5 rounded-lg transition"
                    >
                        Edit Profil
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection