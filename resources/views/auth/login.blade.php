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
    <div class="flex min-h-screen items-center justify-center px-4 py-5">

        <div class="mx-auto w-full max-w-3xl overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-xl">
            <div class="grid lg:grid-cols-2">

                <!-- Panel Informasi -->
                <div class="hidden bg-stone-950 p-7 text-white lg:flex lg:flex-col">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-400 font-black text-stone-900">
                            TP
                        </div>
                        <p class="font-bold">Toko Pecah Belah</p>
                    </div>

                    <div class="my-auto space-y-3 py-8">
                        <p class="text-xs font-semibold uppercase tracking-widest text-amber-200">
                            Sistem Informasi Toko
                        </p>

                        <h1 class="text-2xl font-black leading-tight">
                            Belanja Mudah, Kebutuhan Terpenuhi
                        </h1>

                        <p class="text-sm leading-relaxed text-stone-300">
                            Temukan berbagai kebutuhan rumah tangga dengan
                            mudah dan nikmati pengalaman berbelanja yang
                            praktis bersama Toko Pecah Belah.
                        </p>
                    </div>
                </div>

                <!-- Panel Login -->
                <div class="flex items-center justify-center bg-stone-50 p-5 sm:p-7 lg:p-8">
                    <div class="w-full max-w-sm">

                        <div class="mb-5 text-center lg:text-left">
                            <h2 class="text-xl font-black text-stone-900">
                                Selamat Datang!
                            </h2>
                            <p class="mt-1.5 text-sm leading-relaxed text-stone-600">
                                Silakan masuk menggunakan akun Anda untuk
                                mengakses dashboard sesuai hak akses.
                            </p>
                        </div>

                        <!-- Pesan Error -->
                        @if ($errors->any())
                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <!-- Form Login -->
                        <form action="{{ route('login.process') }}" method="POST" class="space-y-3">
                            @csrf

                            <div>
                                <label for="email" class="mb-1.5 block text-sm font-medium text-stone-700">
                                    Email
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="w-full rounded-lg border border-stone-300 bg-white px-3.5 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100"
                                    placeholder="Masukkan alamat email"
                                    autocomplete="email"
                                    required
                                >
                            </div>

                            <div>
                                <label for="password" class="mb-1.5 block text-sm font-medium text-stone-700">
                                    Password
                                </label>
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    class="w-full rounded-lg border border-stone-300 bg-white px-3.5 py-2.5 text-sm text-stone-900 placeholder:text-stone-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-100"
                                    placeholder="Masukkan password"
                                    autocomplete="current-password"
                                    required
                                >
                            </div>

                            <button
                                type="submit"
                                class="mt-1 inline-flex w-full items-center justify-center rounded-lg bg-stone-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                            >
                                Login
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</body>
</html>
