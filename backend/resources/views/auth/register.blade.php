<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen py-8">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">

        <h3 class="text-2xl font-bold text-center text-gray-800 mb-2">
            Daftar Akun
        </h3>

        <p class="text-sm text-gray-500 text-center mb-6">
            Buat akun untuk menggunakan Sistem Peminjaman Alat
        </p>

        <!-- Pesan Error Validasi -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Register -->
        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <!-- Nama Lengkap -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Nama Lengkap
                </label>

                <input type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Email
                </label>

                <input type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Nomor HP -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Nomor HP
                </label>

                <input type="text"
                    name="no_hp"
                    value="{{ old('no_hp') }}"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Alamat
                </label>

                <textarea name="alamat"
                    rows="3"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('alamat') }}</textarea>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Password
                </label>

                <div class="relative">
                    <input type="password"
                        name="password"
                        id="password"
                        required
                        class="w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <button type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">

                        <!-- Icon Mata Terbuka -->
                        <svg class="eye-icon w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                        </svg>

                        <!-- Icon Mata Dicoret -->
                        <svg class="eye-off-icon hidden w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3l18 18M10.584 10.587a2 2 0 002.829 2.829" />
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.88 5.09A10.94 10.94 0 0112 4.9c4.477 0 8.268 2.943 9.542 7a10.97 10.97 0 01-4.132 5.411M6.61 6.61A11.02 11.02 0 002.458 12c1.274 4.057 5.065 7 9.542 7 1.16 0 2.27-.2 3.29-.57" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">
                    Konfirmasi Password
                </label>

                <div class="relative">
                    <input type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        required
                        class="w-full px-3 py-2 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <button type="button"
                        onclick="togglePassword('password_confirmation', this)"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700">

                        <!-- Icon Mata Terbuka -->
                        <svg class="eye-icon w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                        </svg>

                        <!-- Icon Mata Dicoret -->
                        <svg class="eye-off-icon hidden w-5 h-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3l18 18M10.584 10.587a2 2 0 002.829 2.829" />
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9.88 5.09A10.94 10.94 0 0112 4.9c4.477 0 8.268 2.943 9.542 7a10.97 10.97 0 01-4.132 5.411M6.61 6.61A11.02 11.02 0 002.458 12c1.274 4.057 5.065 7 9.542 7 1.16 0 2.27-.2 3.29-.57" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Tombol Daftar -->
            <button type="submit"
                class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Daftar
            </button>

        </form>

        <!-- Link Login -->
        <p class="text-sm text-center text-gray-600 mt-5">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                class="text-blue-600 font-semibold hover:underline">
                Login di sini
            </a>
        </p>

    </div>

    <!-- JavaScript Toggle Password -->
    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeIcon = button.querySelector('.eye-icon');
            const eyeOffIcon = button.querySelector('.eye-off-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>

</body>
</html>