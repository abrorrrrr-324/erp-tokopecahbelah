@extends('layouts.dashboard')

@section('title', 'Dashboard Kepala Gudang')
@section('page_kicker', 'Gudang')
@section('page_title', 'Kepala Gudang')

@section('content')
    @php
        $stats = [
            ['title' => 'Stok Real', 'value' => '1.248'],
            ['title' => 'Barang keluar', 'value' => '36'],
            ['title' => 'Butuh cek', 'value' => '9'],
            ['title' => 'Audit log', 'value' => '128'],
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
                <h2 class="text-xl font-bold text-stone-900">Monitoring stok</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Live</span>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Stok barang utama</p>
                        <p class="text-sm text-stone-500">Jumlah real time sesuai rak gudang</p>
                    </div>
                    <span class="text-xl">📦</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Pengecekan stok</p>
                        <p class="text-sm text-stone-500">9 item perlu validasi fisik</p>
                    </div>
                    <span class="text-xl">🔍</span>
                </div>
                <div class="flex items-center justify-between rounded-2xl bg-stone-50 p-4">
                    <div>
                        <p class="font-semibold text-stone-900">Mutasi barang keluar</p>
                        <p class="text-sm text-stone-500">36 permintaan hari ini</p>
                    </div>
                    <span class="text-xl">🚚</span>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h2 class="text-xl font-bold text-stone-900">Tugas prioritas</h2>
            <ul class="mt-5 space-y-3 text-sm text-stone-600">
                <li class="flex gap-3"><span class="mt-1 text-emerald-500">●</span> Verifikasi stok fisik dan audit log</li>
                <li class="flex gap-3"><span class="mt-1 text-emerald-500">●</span> Update kuantitas barang yang masuk/keluar</li>
                <li class="flex gap-3"><span class="mt-1 text-emerald-500">●</span> Menyelesaikan pengecekan supplier</li>
                <li class="flex gap-3"><span class="mt-1 text-emerald-500">●</span> Menyusun laporan mutasi dan stok</li>
            </ul>
        </div>
    </section>
@endsection
