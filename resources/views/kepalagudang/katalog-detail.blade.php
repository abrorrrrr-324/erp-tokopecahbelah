@extends('layouts.dashboard')

@section('title', 'Detail Produk Gudang')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Detail Produk')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Data persediaan supplier</p>
                <h2 class="mt-1 text-2xl font-bold text-stone-900">{{ $product->nama }}</h2>
                <p class="mt-1 text-sm text-stone-500">SKU: {{ $product->sku }}</p>
            </div>
            <a
                href="{{ route('gudang.katalog') }}"
                class="rounded-xl border border-stone-200 px-4 py-2.5 text-center text-sm font-semibold text-stone-700 transition hover:bg-stone-50"
            >
                Kembali ke daftar
            </a>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Kategori</p>
                <p class="mt-2 font-bold text-stone-900">{{ $product->category?->nama ?? '-' }}</p>
            </div>
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Supplier</p>
                <p class="mt-2 font-bold text-stone-900">{{ $product->supplier?->nama ?? '-' }}</p>
            </div>
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Status Produk</p>
                <p class="mt-2 font-bold text-stone-900">{{ ucfirst($product->status) }}</p>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h3 class="text-xl font-bold text-stone-900">Persediaan dan Harga</h3>
            <dl class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl bg-stone-50 p-4">
                    <dt class="text-sm text-stone-500">Stok Tersedia</dt>
                    <dd class="mt-2 text-xl font-bold text-stone-900">
                        {{ number_format($product->stok, 0, ',', '.') }} {{ $product->satuan ?: 'unit' }}
                    </dd>
                </div>
                <div class="rounded-2xl bg-stone-50 p-4">
                    <dt class="text-sm text-stone-500">Batas Minimum</dt>
                    <dd class="mt-2 text-xl font-bold text-stone-900">
                        {{ number_format($product->stok_minimum ?? 10, 0, ',', '.') }} {{ $product->satuan ?: 'unit' }}
                    </dd>
                </div>
                <div class="rounded-2xl bg-stone-50 p-4">
                    <dt class="text-sm text-stone-500">Harga Beli</dt>
                    <dd class="mt-2 text-xl font-bold text-stone-900">
                        Rp {{ number_format((float) $product->harga_beli, 0, ',', '.') }}
                    </dd>
                </div>
                <div class="rounded-2xl bg-stone-50 p-4">
                    <dt class="text-sm text-stone-500">Harga Jual</dt>
                    <dd class="mt-2 text-xl font-bold text-stone-900">
                        Rp {{ number_format((float) $product->harga_jual, 0, ',', '.') }}
                    </dd>
                </div>
            </dl>

            @if ($product->deskripsi)
                <div class="mt-5 rounded-2xl border border-stone-100 p-4">
                    <p class="text-sm font-semibold text-stone-700">Deskripsi</p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-stone-600">{{ $product->deskripsi }}</p>
                </div>
            @endif
        </section>
    </div>
@endsection
