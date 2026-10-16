@extends('layouts.dashboard')

@section('title', 'Dashboard Customer')
@section('page_kicker', 'Customer')
@section('page_title', 'Customer')

@section('content')
    @php
        $stats = [
            ['title' => 'Pesanan aktif', 'value' => '05'],
            ['title' => 'Dalam proses', 'value' => '02'],
            ['title' => 'Selesai', 'value' => '18'],
            ['title' => 'Riwayat', 'value' => '87'],
        ];
    @endphp

    <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">{{ $stat['title'] }}</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </section>

    <section class="mt-8 grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-xl font-bold text-stone-900">Pesanan terbaru</h2>
                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Update</span>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Barang kebutuhan rumah</p>
                        <p class="text-sm text-stone-500">Status: dalam proses</p>
                    </div>
                    <span class="text-xl">🛍️</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Detail produk</p>
                        <p class="text-sm text-stone-500">Lihat spesifikasi dan stok</p>
                    </div>
                    <span class="text-xl">📄</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Riwayat pesanan</p>
                        <p class="text-sm text-stone-500">Semua pembelian sebelumnya</p>
                    </div>
                    <span class="text-xl">🕘</span>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-stone-900">Aktivitas customer</h2>
            <ul class="mt-5 space-y-3 text-sm text-stone-600">
                <li class="flex gap-3"><span class="mt-1 text-amber-500">●</span> Melihat katalog dan detail barang</li>
                <li class="flex gap-3"><span class="mt-1 text-amber-500">●</span> Membuat pesanan baru</li>
                <li class="flex gap-3"><span class="mt-1 text-amber-500">●</span> Memantau status dan riwayat pesanan</li>
                <li class="flex gap-3"><span class="mt-1 text-amber-500">●</span> Menerima informasi pengiriman dan update</li>
            </ul>
        </div>
    </section>
@endsection
