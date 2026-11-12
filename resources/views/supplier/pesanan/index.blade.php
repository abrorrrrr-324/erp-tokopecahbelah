@extends('layouts.dashboard')

@section('title', 'Pesanan Pembelian')
@section('page_kicker', 'Supplier')
@section('page_title', 'Pesanan Pembelian')

@section('content')
    <section class="space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-stone-500">Daftar purchase order yang dikirim admin kepada supplier Anda.</p>
        </div>

        @forelse ($orders as $order)
            <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 border-b border-stone-100 pb-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-stone-900">PO-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-sm text-stone-500">{{ $order->tanggal_pesanan?->translatedFormat('d M Y, H:i') }} · Dibuat oleh {{ $order->user?->name ?? 'Admin' }}</p>
                    </div>
                    <span class="w-fit rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">{{ ucfirst($order->status) }}</span>
                </div>

                <div class="mt-4 space-y-3">
                    @foreach ($order->items as $item)
                        <div class="flex flex-col justify-between gap-1 text-sm sm:flex-row sm:items-center">
                            <div>
                                <p class="font-semibold text-stone-900">{{ $item->product?->nama ?? 'Produk dihapus' }} <span class="font-normal text-stone-500">({{ $item->product?->sku ?? '-' }})</span></p>
                                <p class="text-stone-500">{{ $item->jumlah }} {{ $item->product?->satuan }} × Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-semibold text-stone-800">Rp {{ number_format((float) $item->sub_total, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                @if ($order->catatan)
                    <p class="mt-4 rounded-xl bg-stone-50 p-3 text-sm text-stone-600"><span class="font-semibold">Catatan admin:</span> {{ $order->catatan }}</p>
                @endif
                <p class="mt-4 border-t border-stone-100 pt-4 text-right text-lg font-black text-stone-900">Total Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
            </article>
        @empty
            <div class="rounded-3xl border border-dashed border-stone-300 bg-white p-10 text-center text-stone-500">
                Belum ada pesanan pembelian untuk supplier Anda.
            </div>
        @endforelse

        <div>{{ $orders->links() }}</div>
    </section>
@endsection
