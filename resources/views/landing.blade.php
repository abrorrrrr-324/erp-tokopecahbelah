<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Toko Pecah Belah | Belanja Sederhana</title>
        <meta
            name="description"
            content="Toko Pecah Belah menyediakan kebutuhan sehari-hari dengan pilihan simpel, harga bersahabat, dan pelayanan ramah."
        >
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }
        </style>
    </head>
    <body class="bg-stone-100 text-stone-800 antialiased">
        <header class="border-b border-stone-200 bg-white/90 backdrop-blur-sm">
            <nav class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500 text-sm font-bold text-white shadow-sm">
                        TP
                    </div>
                    <div>
                        <p class="text-lg font-bold text-stone-900">Toko Pecah Belah</p>
                    </div>
                </div>

                <div class="hidden items-center gap-6 text-sm font-medium text-stone-600 md:flex">
                    <a href="#produk" class="transition hover:text-amber-600">Produk</a>
                    <a href="#keunggulan" class="transition hover:text-amber-600">Keunggulan</a>
                    <a href="#kontak" class="transition hover:text-amber-600">Kontak</a>
                </div>

                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-stone-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-stone-700"
                >
                    Masuk
                </a>
            </nav>
        </header>

        <main>
            <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="grid items-center gap-10 lg:grid-cols-[1.3fr_0.7fr]">
                    <div>
                        <span class="inline-flex rounded-full border border-amber-200 bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">
                            Belanja gampang
                        </span>
                        <h1 class="mt-5 text-4xl font-black tracking-tight text-stone-900 sm:text-5xl">
                            Toko yang sederhana, tetapi tetap siap melayani kebutuhan Anda.
                        </h1>
                        <p class="mt-5 max-w-xl text-lg leading-8 text-stone-600">
                            Toko Pecah Belah hadir untuk membantu Anda membeli kebutuhan harian dengan pilihan yang simpel,
                            harga yang ramah, dan layanan yang tetap hangat.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-4">
                            <a
                                href="#produk"
                                class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-600"
                            >
                                Lihat Produk
                            </a>
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-stone-300 bg-white px-5 py-3 text-sm font-semibold text-stone-700 transition hover:border-stone-400"
                            >
                                Login Admin
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-stone-200 bg-white p-6 shadow-sm">
                        <div class="rounded-[1.5rem] bg-gradient-to-br from-stone-900 via-stone-800 to-amber-500 p-6 text-white">
                            <div class="flex items-center justify-between">
                                <p class="text-xs uppercase tracking-[0.2em] text-amber-200">Best seller</p>
                                <span class="rounded-full bg-white/10 px-2 py-1 text-[10px] font-medium text-amber-100">
                                    Promo
                                </span>
                            </div>

                            <div class="mt-8 space-y-4">
                                <div class="rounded-2xl bg-white/10 p-4">
                                    <p class="text-sm text-amber-100">Kebutuhan rumah</p>
                                    <p class="mt-2 text-2xl font-black">Rp 129.000</p>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-2xl bg-white/10 p-4">
                                        <div class="text-2xl">🧴</div>
                                        <p class="mt-3 text-sm text-amber-100">Cuci</p>
                                        <p class="mt-1 font-bold">Rp 35k</p>
                                    </div>
                                    <div class="rounded-2xl bg-white/10 p-4">
                                        <div class="text-2xl">🧺</div>
                                        <p class="mt-3 text-sm text-amber-100">Rumah</p>
                                        <p class="mt-1 font-bold">Rp 89k</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="produk" class="bg-white py-16">
                <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-8 text-center">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-600">Produk</p>
                        <h2 class="mt-3 text-3xl font-black text-stone-900">Pilihan yang kami sediakan</h2>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                            <div class="text-3xl">🧼</div>
                            <h3 class="mt-4 text-xl font-bold text-stone-900">Rumah Tangga</h3>
                            <p class="mt-2 text-stone-600">Peralatan harian yang membantu aktivitas rumah jadi lebih mudah.</p>
                        </div>
                        <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                            <div class="text-3xl">📱</div>
                            <h3 class="mt-4 text-xl font-bold text-stone-900">Elektronik</h3>
                            <p class="mt-2 text-stone-600">Produk ringan, praktis, dan cocok untuk kebutuhan sehari-hari.</p>
                        </div>
                        <div class="rounded-3xl border border-stone-200 bg-stone-50 p-5">
                            <div class="text-3xl">🧱</div>
                            <h3 class="mt-4 text-xl font-bold text-stone-900">Perlengkapan</h3>
                            <p class="mt-2 text-stone-600">Barang kebutuhan umum dengan kualitas yang nyaman digunakan.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="keunggulan" class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="text-center">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-amber-600">Keunggulan</p>
                    <h2 class="mt-3 text-3xl font-black text-stone-900">Kenapa pelanggan memilih kami</h2>
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-3">
                    <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-2xl">✅</div>
                        <h3 class="mt-4 text-xl font-bold text-stone-900">Produk terjamin</h3>
                        <p class="mt-2 text-stone-600">Dipilih dengan kualitas yang layak untuk kebutuhan harian.</p>
                    </div>
                    <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-2xl">💸</div>
                        <h3 class="mt-4 text-xl font-bold text-stone-900">Harga bersahabat</h3>
                        <p class="mt-2 text-stone-600">Harga jelas dan tetap ramah untuk kebutuhan keluarga maupun usaha.</p>
                    </div>
                    <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-100 text-2xl">🤝</div>
                        <h3 class="mt-4 text-xl font-bold text-stone-900">Layanan ramah</h3>
                        <p class="mt-2 text-stone-600">Tim kami siap membantu memilih barang yang paling cocok dengan kebutuhan Anda.</p>
                    </div>
                </div>
            </section>
        </main>

        <footer id="kontak" class="bg-stone-900 text-stone-200">
            <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xl font-bold text-white">Toko Pecah Belah</p>
                        <p class="mt-1 text-sm text-stone-400">Belanja sederhana, aman, dan praktis.</p>
                    </div>
                    <a href="{{ route('login') }}" class="text-sm font-medium text-amber-300 transition hover:text-amber-200">
                        Masuk ke sistem
                    </a>
                </div>
            </div>
        </footer>
    </body>
</html>
