@extends('layouts.dashboard')

@section('title', 'Laporan Gudang')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Laporan Stok dan Mutasi')

@section('content')
    <section class="mt-8 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Laporan Gudang</h2>
                <p class="text-sm text-stone-500">Stok saat ini, mutasi masuk/keluar, serta audit barang rusak dan hilang.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('gudang.laporan.print', request()->query()) }}" target="_blank" rel="noopener" class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:bg-stone-50">Cetak</a>
                <a href="{{ route('gudang.laporan.pdf', request()->query()) }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:bg-stone-50">Ekspor PDF</a>
                <a href="{{ route('gudang.laporan.excel', request()->query()) }}" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">Ekspor Excel</a>
            </div>
        </div>

        <form method="GET" action="{{ route('gudang.laporan') }}" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <select name="category_id" class="rounded-xl border border-stone-200 px-3 py-3 text-sm">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->nama }}</option>
                @endforeach
            </select>
            <select name="produk_id" class="rounded-xl border border-stone-200 px-3 py-3 text-sm">
                <option value="">Semua barang</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected(($filters['produk_id'] ?? '') == $product->id)>{{ $product->nama }}</option>
                @endforeach
            </select>
            <select name="jenis" class="rounded-xl border border-stone-200 px-3 py-3 text-sm">
                <option value="">Semua jenis transaksi</option>
                @foreach (['masuk' => 'Barang masuk', 'keluar' => 'Barang keluar', 'rusak' => 'Barang rusak', 'hilang' => 'Barang hilang'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['jenis'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="rounded-xl border border-stone-200 px-3 py-3 text-sm" aria-label="Tanggal mulai">
            <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="rounded-xl border border-stone-200 px-3 py-3 text-sm" aria-label="Tanggal selesai">
            <div class="flex gap-2 sm:col-span-2 xl:col-span-5">
                <button class="rounded-xl bg-stone-900 px-5 py-3 text-sm font-semibold text-white">Terapkan Filter</button>
                <a href="{{ route('gudang.laporan') }}" class="rounded-xl border border-stone-200 px-5 py-3 text-sm font-semibold text-stone-700">Reset</a>
            </div>
        </form>
    </section>

    <section class="mt-6 space-y-6">
        <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-stone-900">Stok Saat Ini</h2>
                    <p class="text-sm text-stone-500">{{ number_format($stocks->total(), 0, ',', '.') }} jenis barang</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-stone-50 text-xs uppercase text-stone-500">
                        <tr><th class="px-4 py-3">SKU</th><th class="px-4 py-3">Barang</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Stok</th><th class="px-4 py-3">Minimum</th><th class="px-4 py-3">Status</th></tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($stocks as $product)
                            @php
                                $minimum = $product->stok_minimum ?? 10;
                                $stockLabel = $product->stok <= 0 ? 'Habis' : ($product->stok <= $minimum ? 'Menipis' : 'Aman');
                            @endphp
                            <tr>
                                <td class="px-4 py-3">{{ $product->sku }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $product->nama }}</td>
                                <td class="px-4 py-3">{{ $product->category?->nama ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $product->stok }} {{ $product->satuan }}</td>
                                <td class="px-4 py-3">{{ $minimum }} {{ $product->satuan }}</td>
                                <td class="px-4 py-3">{{ $stockLabel }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-stone-500">Tidak ada stok sesuai filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $stocks->links() }}</div>
        </article>

        <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-stone-900">Mutasi Barang Masuk dan Keluar</h2>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-stone-50 text-xs uppercase text-stone-500">
                        <tr><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Barang</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Jenis</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Petugas</th><th class="px-4 py-3">Catatan</th></tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($movements as $movement)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3">{{ $movement->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $movement->produk?->nama ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $movement->produk?->category?->nama ?? '-' }}</td>
                                <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $movement->jenis) }}</td>
                                <td class="px-4 py-3">{{ $movement->jumlah }} {{ $movement->produk?->satuan }}</td>
                                <td class="px-4 py-3">{{ $movement->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $movement->catatan ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-stone-500">Tidak ada mutasi sesuai filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $movements->links() }}</div>
        </article>

        <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-bold text-stone-900">Barang Rusak dan Hilang</h2>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-stone-50 text-xs uppercase text-stone-500">
                        <tr><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Barang</th><th class="px-4 py-3">Kategori</th><th class="px-4 py-3">Jenis</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Penyebab / Tindak Lanjut</th></tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($audits as $audit)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-3">{{ $audit->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $audit->produk?->nama ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $audit->produk?->category?->nama ?? '-' }}</td>
                                <td class="px-4 py-3 capitalize">{{ $audit->jenis }}</td>
                                <td class="px-4 py-3">{{ $audit->jumlah }} {{ $audit->produk?->satuan }}</td>
                                <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $audit->status) }}</td>
                                <td class="px-4 py-3">{{ $audit->penyebab }}{{ $audit->tindak_lanjut ? ' — '.$audit->tindak_lanjut : '' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-8 text-center text-stone-500">Tidak ada audit sesuai filter.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $audits->links() }}</div>
        </article>
    </section>
@endsection
