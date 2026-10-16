@extends('layouts.dashboard')

@section('title', 'Dashboard Admin Toko')
@section('page_kicker', 'Dashboard')
@section('page_title', 'Admin Toko')

@section('content')
    @php
        $stats = [
            ['title' => 'Total Penjualan', 'value' => 'Rp 24,8 Jt'],
            ['title' => 'Pesanan Baru', 'value' => '18'],
            ['title' => 'Customer', 'value' => '312'],
            ['title' => 'Supplier', 'value' => '14'],
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
                <h2 class="text-xl font-bold text-stone-900">Aktivitas utama</h2>
                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Hari ini</span>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Mengecek katalog produk</p>
                        <p class="text-sm text-stone-500">Produk baru masuk</p>
                    </div>
                    <span class="text-xl">📦</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Mengelola customer</p>
                        <p class="text-sm text-stone-500">Pembaruan data pelanggan</p>
                    </div>
                    <span class="text-xl">👥</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Memproses penjualan</p>
                        <p class="text-sm text-stone-500">Pesanan menunggu konfirmasi</p>
                    </div>
                    <span class="text-xl">🧾</span>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-stone-900">Prioritas tugas</h2>
            <ul class="mt-5 space-y-3 text-sm text-stone-600">
                <li class="flex gap-3"><span class="mt-1 text-amber-500">●</span> Mengelola katalog produk dan promo</li>
                <li class="flex gap-3"><span class="mt-1 text-amber-500">●</span> Menyelesaikan pesanan customer</li>
                <li class="flex gap-3"><span class="mt-1 text-amber-500">●</span> Mengecek laporan penjualan harian</li>
                <li class="flex gap-3"><span class="mt-1 text-amber-500">●</span> Memantau status stok dan mutasi</li>
            </ul>
        </div>
    </section>
@endsection
