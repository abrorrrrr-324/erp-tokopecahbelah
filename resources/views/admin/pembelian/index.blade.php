@extends('layouts.dashboard')

@section('title', 'Pembelian')
@section('page_kicker', 'Admin')
@section('page_title', 'Pembelian Barang')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Buat pesanan berdasarkan katalog setiap supplier</p>
                <h2 class="mt-1 text-2xl font-bold text-stone-900">Purchase Order</h2>
            </div>
            <a href="{{ route('admin.pembelian.create') }}" class="rounded-xl bg-amber-500 px-4 py-2.5 text-center text-sm font-semibold text-stone-900 hover:bg-amber-400">
                + Buat PO Baru
            </a>
        </div>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">PO Bulan Ini</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['ordersThisMonth'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Menunggu Supplier</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['waiting'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Nilai PO Bulan Ini</p>
                <p class="mt-3 text-2xl font-black text-stone-900">Rp {{ number_format((float) $stats['valueThisMonth'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Supplier Aktif</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['activeSuppliers'] }}</p>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 text-xl font-bold text-stone-900">Daftar Pembelian</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Kode PO</th>
                            <th class="pb-3 font-semibold">Supplier</th>
                            <th class="pb-3 font-semibold">Tanggal</th>
                            <th class="pb-3 font-semibold">Produk</th>
                            <th class="pb-3 font-semibold">Total</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-stone-900">PO-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 pr-4">{{ $order->supplier?->nama ?? 'Supplier tidak tersedia' }}</td>
                                <td class="py-3 pr-4">{{ $order->tanggal_pesanan?->translatedFormat('d M Y') }}</td>
                                <td class="py-3 pr-4">{{ $order->items->count() }} item</td>
                                <td class="py-3 pr-4">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $order->status === 'menunggu' ? 'bg-amber-100 text-amber-700' : 'bg-stone-100 text-stone-700' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <a href="{{ route('admin.pembelian.show', $order) }}" class="font-semibold text-amber-700 hover:text-amber-600">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-stone-500">Belum ada pesanan pembelian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-5">{{ $orders->links() }}</div>
        </section>
    </div>
@endsection
