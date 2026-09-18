@extends('layouts.app')

@section('title', 'Edit Profil')
@section('header-title', 'Edit Profil')

@section('content')

<div class="max-w-4xl bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

    {{-- Header --}}
    <div class="bg-gray-900 px-6 py-5 text-white">
        <h2 class="text-xl font-semibold">
            Edit Profil
        </h2>

        <p class="text-sm text-gray-300 mt-1">
            Ubah informasi profil dan foto akun kamu
        </p>
    </div>

    {{-- Form --}}
    <form
        action="{{ route('admin.profile.update') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="p-6">

            {{-- Error --}}
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- FOTO --}}
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

                    <label class="mt-4 text-sm font-medium text-gray-700">
                        Foto Profil
                    </label>

                    <input
                        type="file"
                        name="foto"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="mt-2 block w-full text-sm text-gray-600
                        file:mr-3 file:py-2 file:px-3
                        file:rounded-lg file:border-0
                        file:bg-gray-100 file:text-gray-700
                        hover:file:bg-gray-200"
                    >

                    <p class="text-xs text-gray-400 mt-2 text-center">
                        JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>

                </div>


                {{-- DATA USER --}}
                <div class="md:col-span-2 space-y-5">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama
                        </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                            focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>


                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                            focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>


                    {{-- Role --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Role
                        </label>

                        <div class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full
                            bg-blue-100 text-blue-700 text-sm font-semibold">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>

                        <p class="text-xs text-gray-400 mt-1">
                            Role tidak dapat diubah melalui profil.
                        </p>
                    </div>


                    {{-- No HP --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            No. HP
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                            focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>


                    {{-- Alamat --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3
                            focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >{{ old('alamat', $user->alamat) }}</textarea>
                    </div>

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end gap-3">

                <a
                    href="{{ route('admin.profile') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700
                    font-medium px-5 py-2.5 rounded-lg transition"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white
                    font-medium px-5 py-2.5 rounded-lg transition"
                >
                    Simpan
                </button>

            </div>

        </div>

    </form>

</div>

@endsection