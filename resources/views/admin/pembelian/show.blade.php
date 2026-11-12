@extends('layouts.dashboard')

@section('title', 'Detail Purchase Order')
@section('page_kicker', 'Admin')
@section('page_title', 'Detail Purchase Order')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Kode pesanan</p>
                <h2 class="mt-1 text-2xl font-black text-stone-900">PO-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <a href="{{ route('admin.pembelian') }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-center text-sm font-semibold text-stone-700 hover:bg-stone-50">Kembali ke daftar</a>
        </section>

        <section class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Supplier</p>
                <p class="mt-1 font-bold text-stone-900">{{ $order->supplier?->nama ?? 'Supplier tidak tersedia' }}</p>
                <p class="text-sm text-stone-500">{{ $order->supplier?->user?->email ?? '-' }}</p>
            </div>
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Tanggal dan status</p>
                <p class="mt-1 font-bold text-stone-900">{{ $order->tanggal_pesanan?->translatedFormat('d M Y, H:i') }}</p>
                <span class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">{{ ucfirst($order->status) }}</span>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 text-xl font-bold text-stone-900">Rincian produk</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Produk</th>
                            <th class="pb-3 font-semibold">Jumlah</th>
                            <th class="pb-3 font-semibold">Harga</th>
                            <th class="pb-3 text-right font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4">
                                    <p class="font-semibold text-stone-900">{{ $item->product?->nama ?? 'Produk dihapus' }}</p>
                                    <p class="text-xs text-stone-500">{{ $item->product?->sku ?? '-' }}</p>
                                </td>
                                <td class="py-3 pr-4">{{ $item->jumlah }} {{ $item->product?->satuan }}</td>
                                <td class="py-3 pr-4">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                                <td class="py-3 text-right font-semibold text-stone-900">Rp {{ number_format((float) $item->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="pt-4 text-right font-bold text-stone-900">Total</td>
                            <td class="pt-4 text-right text-lg font-black text-stone-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if ($order->catatan)
                <div class="mt-5 rounded-2xl bg-stone-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">Catatan</p>
                    <p class="mt-1 text-sm text-stone-700">{{ $order->catatan }}</p>
                </div>
            @endif
        </section>
    </div>
@endsection
