@extends('layouts.dashboard')

@section('title', 'Cek Stok')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Cek Stok')

@section('content')

    {{-- Statistik Stok --}}
    <section class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Total Produk</p>
            <h3 class="mt-2 text-2xl font-bold text-stone-900">
                {{ $totalProduk }}
            </h3>
            <p class="mt-1 text-xs text-stone-500">Jenis barang terdaftar</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Total Stok</p>
            <h3 class="mt-2 text-2xl font-bold text-stone-900">
                {{ number_format($totalStok, 0, ',', '.') }}
            </h3>
            <p class="mt-1 text-xs text-stone-500">Seluruh unit barang</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Stok Menipis</p>
            <h3 class="mt-2 text-2xl font-bold text-amber-600">
                {{ $stokMenipis }}
            </h3>
            <p class="mt-1 text-xs text-stone-500">Perlu perhatian</p>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-stone-500">Stok Habis</p>
            <h3 class="mt-2 text-2xl font-bold text-red-600">
                {{ $stokHabis }}
            </h3>
            <p class="mt-1 text-xs text-stone-500">Barang tidak tersedia</p>
        </div>

    </section>

    {{-- Daftar Produk --}}
    <section class="mt-6 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">

        <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Daftar Stok Barang</h2>
                <p class="text-sm text-stone-500">Pantau ketersediaan stok seluruh produk di gudang</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('gudang.stok.mutasi') }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:bg-stone-50">Riwayat Mutasi</a>
                <a href="{{ route('gudang.stok.mutasi.create') }}" class="rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-bold text-stone-900 hover:bg-amber-400">Catat Barang Masuk / Keluar</a>
            </div>
        </div>

        {{-- Pencarian --}}
        <form action="{{ route('gudang.cek-stok') }}" method="GET" class="mb-6 flex flex-col gap-3 sm:flex-row">

            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama produk, kategori..."
                    class="w-full rounded-xl border border-stone-200 bg-stone-50 py-3 pl-10 pr-4 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-amber-400 focus:bg-white focus:ring-2 focus:ring-amber-100">
            </div>

            <div>
                <label for="status_stok" class="sr-only">Filter status stok</label>
                <select
                    id="status_stok"
                    name="status_stok"
                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-amber-400 focus:bg-white focus:ring-2 focus:ring-amber-100 sm:min-w-48"
                >
                    <option value="">Semua status stok</option>
                    <option value="habis" {{ request('status_stok') === 'habis' ? 'selected' : '' }}>Habis</option>
                    <option value="menipis" {{ request('status_stok') === 'menipis' ? 'selected' : '' }}>Menipis</option>
                    <option value="aman" {{ request('status_stok') === 'aman' ? 'selected' : '' }}>Aman</option>
                </select>
            </div>

            <button type="submit"
                class="rounded-xl bg-stone-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-stone-700">
                Cari
            </button>

            @if (request('search') || request('status_stok'))
                <a href="{{ route('gudang.cek-stok') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-stone-200 px-5 py-3 text-sm font-semibold text-stone-600 hover:bg-stone-100">
                    Reset
                </a>
            @endif

        </form>

        {{-- Tabel Stok Produk --}}
        @if ($products->isEmpty())
            <div
                class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-4 py-10 text-center text-sm text-stone-500">
                Produk tidak ditemukan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[800px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                            <th class="px-4 py-3 font-semibold">Produk</th>
                            <th class="px-4 py-3 font-semibold">Kategori</th>
                            <th class="px-4 py-3 font-semibold">Stok Tersedia</th>
                            <th class="px-4 py-3 font-semibold">Batas Minimum</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($products as $product)
                            @php
                                $minimumStock = $product->stok_minimum ?? 10;

                                if ($product->stok <= 0) {
                                    $stockStatus = 'Habis';
                                    $statusClass = 'bg-red-100 text-red-700';
                                } elseif ($product->stok <= $minimumStock) {
                                    $stockStatus = 'Menipis';
                                    $statusClass = 'bg-amber-100 text-amber-700';
                                } else {
                                    $stockStatus = 'Aman';
                                    $statusClass = 'bg-emerald-100 text-emerald-700';
                                }
                            @endphp
                            <tr class="transition hover:bg-stone-50">
                                <td class="px-4 py-4 font-semibold text-stone-900">{{ $product->nama }}</td>
                                <td class="px-4 py-4 text-stone-600">{{ $product->category?->nama ?? '-' }}</td>
                                <td class="whitespace-nowrap px-4 py-4 font-semibold text-stone-900">
                                    {{ number_format($product->stok, 0, ',', '.') }} {{ $product->satuan ?: 'unit' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-stone-600">
                                    {{ number_format($minimumStock, 0, ',', '.') }} {{ $product->satuan ?: 'unit' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        {{ $stockStatus }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right">
                                    <a
                                        href="{{ route('gudang.katalog.show', $product) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-stone-900 transition hover:bg-amber-400"
                                    >
                                        Lihat detail
                                    </a>
                                    <a
                                        href="{{ route('gudang.stok.mutasi', ['produk_id' => $product->id]) }}"
                                        class="ml-1 inline-flex items-center justify-center rounded-lg border border-stone-200 px-3 py-2 text-xs font-semibold text-stone-700 hover:bg-stone-100"
                                    >
                                        Riwayat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </section>

@endsection
