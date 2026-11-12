@extends('layouts.dashboard')

@section('title', 'Dashboard Supplier')
@section('page_kicker', 'Supplier')
@section('page_title', 'Ringkasan Supplier')

@section('content')
    <div class="space-y-7">
        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Total Pesanan</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['orders'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Menunggu Konfirmasi</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['waiting'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Produk Katalog</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['products'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Produk Aktif</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['activeProducts'] }}</p>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-stone-900">Pesanan terbaru</h2>
                    <p class="mt-1 text-sm text-stone-500">Pesanan yang dibuat admin dari katalog supplier Anda.</p>
                </div>
                <a href="{{ route('supplier.pesanan') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-600">Lihat semua pesanan</a>
            </div>

            <div class="space-y-3">
                @forelse ($orders as $order)
                    <a href="{{ route('supplier.pesanan') }}" class="flex flex-col gap-3 rounded-2xl bg-stone-50 p-4 transition hover:bg-stone-100 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-stone-900">PO-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-sm text-stone-500">{{ $order->tanggal_pesanan?->translatedFormat('d M Y') }} · {{ $order->items->count() }} item</p>
                        </div>
                        <div class="sm:text-right">
                            <p class="font-semibold text-stone-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                            <p class="text-xs text-amber-700">{{ ucfirst($order->status) }}</p>
                        </div>
                    </a>
                @empty
                    <p class="rounded-2xl bg-stone-50 p-5 text-sm text-stone-500">Belum ada pesanan. Pastikan produk dan harga pada katalog Anda selalu terbaru.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
