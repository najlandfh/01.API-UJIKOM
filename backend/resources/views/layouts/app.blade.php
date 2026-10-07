<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard')</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        {{-- ==================== SIDEBAR ==================== --}}
        <aside class="w-64 bg-gray-900 text-white flex flex-col hidden md:flex">

            {{-- JUDUL PANEL --}}
            <div class="p-5 text-xl font-bold tracking-wider border-b border-gray-800">
                <h1>

                    @if(auth()->user()->role === 'admin')
                        PANEL ADMIN

                    @elseif(auth()->user()->role === 'petugas')
                        PANEL PETUGAS

                    @elseif(auth()->user()->role === 'peminjam')
                        PANEL PEMINJAM
                    @endif

                </h1>
            </div>


            {{-- ==================== MENU ==================== --}}
            <nav class="flex-1 p-4 space-y-2">

                {{-- ==================== MENU ADMIN ==================== --}}
                @if(auth()->user()->role === 'admin')

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.dashboard')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Dashboard
                    </a>


                    <a
                        href="{{ route('admin.user.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.user.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola User
                    </a>


                    <a
                        href="{{ route('admin.kategori.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.kategori.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Kategori
                    </a>


                    <a
                        href="{{ route('admin.alat.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.alat.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Alat
                    </a>


                    <a
                        href="{{ route('admin.peminjaman.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.peminjaman.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Peminjaman
                    </a>


                    <a
                        href="{{ route('admin.pengembalian.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.pengembalian.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Kelola Pengembalian
                    </a>

                    <a
                        href="{{ route('admin.log-aktivitas.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('admin.log-aktivitas.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Log Aktivitas
                    </a>

                @endif


                {{-- ==================== MENU PETUGAS ==================== --}}
                @if(auth()->user()->role === 'petugas')

                    <a
                        href="{{ route('petugas.peminjaman.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('petugas.peminjaman.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Persetujuan Peminjaman
                    </a>


                    <a
                        href="{{ route('petugas.pengembalian.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('petugas.pengembalian.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Pemantauan Pengembalian
                    </a>


                    <a
                        href="{{ route('petugas.laporan.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('petugas.laporan.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Cetak Laporan
                    </a>

                @endif


                {{-- ==================== MENU PEMINJAM ==================== --}}
                @if(auth()->user()->role === 'peminjam')

                    <a
                        href="{{ route('peminjam.alat.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('peminjam.alat.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Katalog Alat
                    </a>


                    <a
                        href="{{ route('peminjam.peminjaman.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('peminjam.peminjaman.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Peminjaman Saya
                    </a>


                    <a
                        href="{{ route('peminjam.pengembalian.index') }}"
                        class="block px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('peminjam.pengembalian.*')
                            ? 'bg-gray-800 text-white font-medium shadow'
                            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}"
                    >
                        Pengembalian
                    </a>

                @endif

            </nav>


            {{-- ==================== PROFIL USER ==================== --}}
            <div class="p-4 border-t border-gray-800">

                @if(auth()->user()->role === 'admin')

                    <a
                        href="{{ route('admin.profile') }}"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-800 transition"
                    >

                @elseif(auth()->user()->role === 'petugas')

                    <a
                        href="{{ route('petugas.profile') }}"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-800 transition"
                    >

                @elseif(auth()->user()->role === 'peminjam')

                    <a
                        href="{{ route('peminjam.profile') }}"
                        class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-800 transition"
                    >

                @endif


                    {{-- FOTO PROFIL --}}
                    <div class="w-11 h-11 rounded-full overflow-hidden bg-gray-700 flex-shrink-0">

                        @if(auth()->user()->foto)

                            <img
                                src="{{ asset('storage/' . auth()->user()->foto) }}"
                                alt="Foto Profil"
                                class="w-full h-full object-cover"
                            >

                        @else

                            <div class="w-full h-full flex items-center justify-center text-gray-400">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6"
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


                    {{-- NAMA DAN ROLE --}}
                    <div class="min-w-0">

                        <p class="text-white font-semibold text-sm truncate">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-gray-400 text-xs mt-0.5">
                            {{ ucfirst(auth()->user()->role) }}
                        </p>

                    </div>


                </a>

            </div>

        </aside>


        {{-- ==================== KONTEN UTAMA ==================== --}}
        <div class="flex-1 flex flex-col overflow-y-auto">


            {{-- ==================== HEADER ==================== --}}
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 z-10">

                <div class="text-lg font-semibold text-gray-800">
                    @yield('header-title', 'Dashboard')
                </div>


                {{-- LOGOUT --}}
                <div>

                    <form action="{{ route('logout') }}" method="POST">

                        @csrf

                        <button
                            type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-4 py-2 rounded-lg transition"
                        >
                            Logout
                        </button>

                    </form>

                </div>

            </header>


            {{-- ==================== CONTENT ==================== --}}
            <main class="flex-1 p-6">

                @yield('content')

            </main>

        </div>

    </div>

</body>

</html>