@extends('layouts.app')

@section('title', 'Edit User - Panel Admin')
@section('header-title', 'Edit Data Pengguna')

@section('content')

<div class="max-w-xl bg-white rounded-lg shadow-sm border border-gray-200 p-6">

    {{-- Notifikasi Error Validasi --}}
    @if ($errors->any())
        <div class="mb-5 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg">
            <p class="font-semibold text-sm mb-2">
                Data belum berhasil diperbarui:
            </p>

            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit User --}}
    <form
        action="{{ route('admin.user.update', $user->id) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
                Nama Lengkap
            </label>

            <input
                type="text"
                name="name"
                value="{{ old('name', $user->name) }}"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            @error('name')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>


        {{-- Email --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
                Email
            </label>

            <input
                type="email"
                name="email"
                value="{{ old('email', $user->email) }}"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            @error('email')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>


        {{-- Password --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
                Password Baru
                <span class="text-xs text-gray-400 font-normal">
                    (Kosongkan jika tidak ingin mengubah password)
                </span>
            </label>

            <input
                type="password"
                name="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            @error('password')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>


        {{-- Role --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
                Role / Hak Akses
            </label>

            <select
                name="role"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option
                    value="peminjam"
                    {{ old('role', $user->role) == 'peminjam' ? 'selected' : '' }}
                >
                    Peminjam
                </option>

                <option
                    value="petugas"
                    {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}
                >
                    Petugas
                </option>

                <option
                    value="admin"
                    {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}
                >
                    Admin
                </option>
            </select>

            @error('role')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>


        {{-- No HP --}}
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-semibold mb-2">
                No. HP
            </label>

            <input
                type="text"
                name="no_hp"
                value="{{ old('no_hp', $user->no_hp) }}"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            @error('no_hp')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>


        {{-- Foto Profil --}}
        <div class="mb-6">

            <label
                for="foto"
                class="block text-gray-700 text-sm font-semibold mb-2"
            >
                Foto Profil
            </label>


            {{-- Foto Saat Ini --}}
            @if($user->foto_profile)

                <div class="mb-4">

                    <p class="text-xs text-gray-500 mb-2">
                        Foto saat ini:
                    </p>

                    <img
                        src="{{ asset('storage/' . $user->foto_profile) }}"
                        alt="Foto {{ $user->name }}"
                        class="w-24 h-24 rounded-full object-cover border-2 border-gray-300"
                    >

                </div>

            @else

                <div class="mb-3">

                    <div class="w-24 h-24 rounded-full bg-gray-700 text-white flex items-center justify-center font-bold text-2xl border-2 border-gray-300">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Belum ada foto profil.
                    </p>

                </div>

            @endif


            {{-- Upload Foto Baru --}}
            <input
                type="file"
                name="foto"
                id="foto"
                accept=".jpg,.jpeg,.png,.webp"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white"
            >

            <p class="text-xs text-gray-500 mt-1">
                Kosongkan jika tidak ingin mengganti foto.
            </p>

            @error('foto')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror

        </div>


        {{-- Tombol --}}
        <div class="flex justify-end space-x-2">

            <a
                href="{{ route('admin.user.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold transition"
            >
                Batal
            </a>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition"
            >
                Perbarui
            </button>

        </div>

    </form>

</div>

@endsection