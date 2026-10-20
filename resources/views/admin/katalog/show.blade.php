@extends('layouts.dashboard')

@section('title', 'Detail Produk')
@section('page_kicker', 'Admin')
@section('page_title', 'Detail Produk')

@section('content')
    @php
        $displaySku = $product->sku ?? 'PRD-' . str_pad((string) $product->id, 4, '0', STR_PAD_LEFT);
        $displayDescription = $product->description ?? $product->deskripsi ?? 'Tidak ada deskripsi produk.';
        $displayUnit = $product->unit ?? $product->satuan ?? 'pcs';
        $displayBuyPrice = $product->buy_price ?? $product->harga_beli ?? 0;
        $displayMinimumStock = $product->minimum_stock ?? $product->stok_minimum ?? 0;
    @endphp

    <div class="mx-auto max-w-5xl space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-stone-500">Informasi lengkap katalog produk</p>
                    <h2 class="mt-1 text-2xl font-bold text-stone-900">{{ $product->name }}</h2>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.katalog') }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:border-stone-300 hover:bg-stone-50">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.1fr_1.9fr]">
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <img src="{{ $product->photo ? Storage::url($product->photo) : 'https://placehold.co/800x800/f5f5f4/1c1917?text=Produk' }}" alt="{{ $product->name }}" class="h-full w-full rounded-2xl object-cover ring-1 ring-stone-200">
            </div>

            <div class="space-y-6 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                        {{ $product->status }}
                    </span>
                    <span class="rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-700">
                        {{ $product->category }}
                    </span>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        SKU: {{ $displaySku }}
                    </span>
                </div>

                <div>
                    <p class="text-sm text-stone-500">Harga Jual</p>
                    <p class="mt-2 text-3xl font-black text-stone-900">Rp {{ number_format((float) ($product->price ?? $product->harga_jual ?? 0), 0, ',', '.') }}</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-sm text-stone-500">Stok</p>
                        <p class="mt-2 text-2xl font-bold text-stone-900">{{ $product->stock ?? $product->stok ?? 0 }} {{ $displayUnit }}</p>
                    </div>
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-sm text-stone-500">Stok Minimum</p>
                        <p class="mt-2 text-lg font-bold text-stone-900">{{ $displayMinimumStock }} {{ $displayUnit }}</p>
                    </div>
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-sm text-stone-500">Harga Beli</p>
                        <p class="mt-2 text-lg font-bold text-stone-900">Rp {{ number_format((float) $displayBuyPrice, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-2xl bg-stone-50 p-4">
                        <p class="text-sm text-stone-500">Satuan</p>
                        <p class="mt-2 text-lg font-bold text-stone-900">{{ $displayUnit }}</p>
                    </div>
                </div>

                <div class="rounded-2xl bg-stone-50 p-4">
                    <p class="text-sm text-stone-500">Deskripsi</p>
                    <p class="mt-2 text-sm leading-6 text-stone-700 whitespace-pre-line">{{ $displayDescription }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
