<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Toko Pecah Belah</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system,
                "Segoe UI", sans-serif;
        }
    </style>
</head>

<body class="bg-stone-100 text-stone-800 antialiased">

    <div class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6">

        <!-- Card Utama -->
        <div class="mx-auto w-full max-w-4xl overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-[0_20px_50px_rgba(28,25,23,0.08)]">

            <div class="grid min-h-[500px] lg:grid-cols-[1fr_1fr]">

                <!-- Panel Informasi -->
                <div class="hidden bg-gradient-to-br from-stone-900 via-stone-800 to-amber-500 p-8 text-white lg:flex lg:flex-col">

                    <!-- Logo dan Nama Toko -->
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-400 text-sm font-black text-stone-900">
                            TP
                        </div>

                        <p class="text-lg font-bold">
                            Toko Pecah Belah
                        </p>
                    </div>

                    <!-- Informasi -->
                    <div class="my-auto space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-200">
                            Sistem Informasi Toko
                        </p>

                        <h1 class="max-w-sm text-3xl font-black leading-tight">
                            Belanja Mudah, Kebutuhan Terpenuhi
                        </h1>

                        <p class="max-w-sm text-sm leading-relaxed text-stone-200">
                            Temukan berbagai kebutuhan rumah tangga dengan
                            mudah dan nikmati pengalaman berbelanja yang
                            praktis bersama Toko Pecah Belah.
                        </p>
                    </div>

                    <!-- Footer Panel -->
                    <p class="text-xs text-stone-300">
                        Praktis untuk kebutuhan sehari-hari.
                    </p>

                </div>

                <!-- Panel Login -->
                <div class="flex items-center justify-center bg-stone-50 p-6 sm:p-8 lg:p-10">

                    <div class="w-full max-w-sm">

                        <!-- Judul Login -->
                        <div class="mb-7 text-center lg:text-left">

                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-amber-600">
                                Login
                            </p>

                            <h2 class="mt-2 text-2xl font-black text-stone-900">
                                Selamat Datang!
                            </h2>

                            <p class="mt-2 text-sm leading-relaxed text-stone-600">
                                Silakan masuk menggunakan akun Anda untuk
                                mengakses dashboard sesuai hak akses.
                            </p>

                        </div>

                        <!-- Pesan Error -->
                        @if ($errors->any())
                            <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <!-- Form Login -->
                        <form
                            action="{{ route('login.process') }}"
                            method="POST"
                            class="space-y-4"
                        >
                            @csrf

                            <!-- Email -->
                            <div>
                                <label
                                    for="email"
                                    class="mb-2 block text-sm font-medium text-stone-700"
                                >
                                    Email
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100"
                                    placeholder="Masukkan alamat email"
                                    autocomplete="email"
                                    required
                                >
                            </div>

                            <!-- Password -->
                            <div>
                                <label
                                    for="password"
                                    class="mb-2 block text-sm font-medium text-stone-700"
                                >
                                    Password
                                </label>

                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-900 placeholder:text-stone-400 transition focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100"
                                    placeholder="Masukkan password"
                                    autocomplete="current-password"
                                    required
                                >
                            </div>

                            <!-- Tombol Login -->
                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-stone-900 px-4 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                            >
                                Masuk ke Dashboard
                            </button>

                        </form>

                        <!-- Keterangan -->
                        <p class="mt-6 text-center text-xs text-stone-500 lg:text-left">
                            Gunakan akun yang telah terdaftar untuk masuk.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>
