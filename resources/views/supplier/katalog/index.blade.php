@extends('layouts.dashboard')

@section('title', 'Katalog Supplier')
@section('page_kicker', 'Supplier')
@section('page_title', 'Katalog Saya')

@section('content')
    <section class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Produk dan harga yang tersedia untuk pesanan toko.</p>
                <h2 class="mt-1 text-xl font-bold text-stone-900">{{ $products->count() }} produk</h2>
            </div>
            <a href="{{ route('supplier.katalog.create') }}" class="rounded-xl bg-amber-500 px-4 py-2.5 text-center text-sm font-semibold text-stone-900 hover:bg-amber-400">+ Tambah Produk</a>
        </div>

        <div class="overflow-hidden rounded-3xl border border-stone-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead class="bg-stone-50 text-stone-500">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Produk</th>
                            <th class="px-5 py-3 font-semibold">Kategori</th>
                            <th class="px-5 py-3 font-semibold">Harga Supplier</th>
                            <th class="px-5 py-3 font-semibold">Stok</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-t border-stone-100">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-stone-900">{{ $product->nama }}</p>
                                    <p class="text-xs text-stone-500">{{ $product->sku }}</p>
                                </td>
                                <td class="px-5 py-4">{{ $product->category?->nama ?? '-' }}</td>
                                <td class="px-5 py-4">Rp {{ number_format((float) $product->harga_beli, 0, ',', '.') }} / {{ $product->satuan }}</td>
                                <td class="px-5 py-4">{{ number_format($product->stok, 0, ',', '.') }} {{ $product->satuan }}</td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-stone-100 text-stone-600' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                    @if ($product->purchase_order_items_count > 0)
                                        <p class="mt-1 text-xs text-stone-500">{{ $product->purchase_order_items_count }} detail PO</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('supplier.katalog.edit', $product->id) }}" class="font-semibold text-amber-700 hover:text-amber-600">Ubah</a>
                                        <form action="{{ route('supplier.katalog.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $product->nama }} dari katalog?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-semibold text-red-600 hover:text-red-500">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-stone-500">
                                    Katalog Anda masih kosong. Tambahkan produk agar admin dapat membuat pesanan pembelian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
