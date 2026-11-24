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

        <div class="mb-5">
            <h2 class="text-xl font-bold text-stone-900">
                Daftar Stok Barang
            </h2>
            <p class="text-sm text-stone-500">
                Pantau ketersediaan stok seluruh produk di gudang
            </p>
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

            <button type="submit"
                class="rounded-xl bg-stone-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-stone-700">
                Cari
            </button>

            @if (request('search'))
                <a href="{{ route('gudang.cek-stok') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-stone-200 px-5 py-3 text-sm font-semibold text-stone-600 hover:bg-stone-100">
                    Reset
                </a>
            @endif

        </form>

        {{-- Kartu Produk --}}
        @if ($products->isEmpty())

            <div
                class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-4 py-10 text-center text-sm text-stone-500">
                Produk tidak ditemukan.
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">

                @foreach ($products as $product)
                    @php
                        $minimumStock = $product->minimum_stock ?? 10;

                        if ($product->stock <= 0) {
                            $status = 'Habis';
                            $statusClass = 'bg-red-100 text-red-700';
                        } elseif ($product->stock <= $minimumStock) {
                            $status = 'Menipis';
                            $statusClass = 'bg-amber-100 text-amber-700';
                        } else {
                            $status = 'Aman';
                            $statusClass = 'bg-green-100 text-green-700';
                        }
                    @endphp

                    <article
                        class="overflow-hidden rounded-2xl border border-stone-200 bg-stone-50 shadow-sm transition hover:shadow-md">

                        {{-- Foto Produk --}}
                        <img src="{{ $product->photo ? Storage::url($product->photo) : 'https://placehold.co/800x800/f5f5f4/1c1917?text=Produk' }}"
                            alt="{{ $product->name }}" class="h-48 w-full object-cover"
                            onerror="this.onerror=null;this.src='https://placehold.co/800x800/f5f5f4/1c1917?text=Produk';">

                        <div class="space-y-4 p-5">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.12em] text-stone-500">
                                    {{ $product->category }}
                                </p>

                                <h3 class="mt-2 text-lg font-bold text-stone-900">
                                    {{ $product->name }}
                                </h3>
                            </div>

                            <div class="flex items-center justify-between text-sm text-stone-600">
                                <span>Stok Tersedia</span>
                                <strong class="font-bold text-stone-900">
                                    {{ number_format($product->stock, 0, ',', '.') }}
                                    {{ $product->unit ?: 'unit' }}
                                </strong>
                            </div>

                            <div class="flex items-center justify-between text-sm text-stone-600">
                                <span>Batas Minimum</span>
                                <strong class="font-bold text-stone-900">
                                    {{ number_format($minimumStock, 0, ',', '.') }}
                                    {{ $product->unit ?: 'unit' }}
                                </strong>
                            </div>

                            <div class="flex items-center justify-between border-t border-stone-200 pt-3">
                                <span class="text-sm text-stone-600">Status Stok</span>

                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </div>

                            <a href="{{ route('gudang.katalog.show', $product) }}"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-amber-500 px-3 py-2.5 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                                Lihat Detail
                            </a>

                        </div>
                    </article>
                @endforeach

            </div>

        @endif

    </section>

@endsection
