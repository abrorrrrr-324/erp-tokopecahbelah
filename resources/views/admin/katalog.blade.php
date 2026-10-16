@extends('layouts.dashboard')

@section('title', 'Katalog Produk')
@section('page_kicker', 'Admin')
@section('page_title', 'Katalog Produk')

@section('content')
    @php
        $statusClasses = [
            'Promo' => 'bg-emerald-100 text-emerald-700',
            'Hampir habis' => 'bg-amber-100 text-amber-700',
            'Aktif' => 'bg-stone-100 text-stone-700',
        ];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Kelola katalog produk toko</p>
                <h2 class="mt-1 text-2xl font-bold text-stone-900">Daftar Produk</h2>
            </div>
            <button type="button" class="rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400">
                + Tambah Produk
            </button>
        </div>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Total Produk</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $totalProduk }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Stok Rendah</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stokRendah }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Kategori</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $kategori }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Nilai Produk</p>
                <p class="mt-3 text-3xl font-black text-stone-900">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</p>
            </div>
        </section>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-xl font-bold text-stone-900">Produk Terbaru</h3>
                <div class="flex gap-2">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Aktif</span>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Promo</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Produk</th>
                            <th class="pb-3 font-semibold">Kategori</th>
                            <th class="pb-3 font-semibold">Stok</th>
                            <th class="pb-3 font-semibold">Harga</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-stone-900">{{ $product->name }}</td>
                                <td class="py-3 pr-4">{{ $product->category }}</td>
                                <td class="py-3 pr-4">{{ $product->stock }}</td>
                                <td class="py-3 pr-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$product->status] ?? 'bg-stone-100 text-stone-700' }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <button type="button" class="text-sm font-semibold text-amber-600 hover:text-amber-500">Edit</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-stone-500">Belum ada produk yang tersimpan di database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
