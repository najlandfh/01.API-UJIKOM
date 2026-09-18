@extends('layouts.app')

@section('title', 'Edit Profil')
@section('header-title', 'Edit Profil')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="bg-gray-900 px-6 py-5 text-white">
            <h2 class="text-xl font-semibold">
                Edit Profil
            </h2>

            <p class="text-sm text-gray-300 mt-1">
                Ubah informasi profil akun petugas
            </p>
        </div>


        {{-- Form --}}
        <form
            action="{{ route('petugas.profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6"
        >

            @csrf
            @method('PUT')


            {{-- FOTO --}}
            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Foto Profil
                </label>

                <div class="flex items-center gap-5">

                    <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-200 bg-gray-100 flex-shrink-0">

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
                                    class="w-10 h-10"
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


                    <div>
                        <input
                            type="file"
                            name="foto"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full text-sm text-gray-600
                            file:mr-3 file:py-2 file:px-3
                            file:rounded-lg file:border-0
                            file:bg-gray-100 file:text-gray-700
                            hover:file:bg-gray-200"
                        >

                        <p class="text-xs text-gray-400 mt-2">
                            Format: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                        </p>
                    </div>

                </div>

            </div>


            {{-- NAMA --}}
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                @error('name')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- EMAIL --}}
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                @error('email')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- ROLE --}}
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Role
                </label>

                <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-3 text-gray-700">
                    {{ ucfirst($user->role) }}
                </div>

                <p class="text-xs text-gray-400 mt-1">
                    Role tidak dapat diubah.
                </p>

            </div>


            {{-- NO HP --}}
            <div class="mb-5">

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    No. HP
                </label>

                <input
                    type="text"
                    name="no_hp"
                    value="{{ old('no_hp', $user->no_hp) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                @error('no_hp')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- ALAMAT --}}
            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Alamat
                </label>

                <textarea
                    name="alamat"
                    rows="4"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >{{ old('alamat', $user->alamat) }}</textarea>

                @error('alamat')
                    <p class="text-sm text-red-600 mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- BUTTON --}}
            <div class="flex justify-end gap-3 pt-5 border-t border-gray-200">

                <a
                    href="{{ route('petugas.profile') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300
                    text-gray-700 hover:bg-gray-100 transition"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600
                    hover:bg-blue-700 text-white font-medium transition"
                >
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection