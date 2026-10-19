@extends('layouts.dashboard')

@section('title', 'Laporan')
@section('page_kicker', 'Admin')
@section('page_title', 'Laporan & Analisis')

@section('content')
    @php
        $reports = [
            ['label' => 'Penjualan Bulan Ini', 'value' => 'Rp 137,9 Jt', 'trend' => '+12.4%'],
            ['label' => 'Profit Bersih', 'value' => 'Rp 48,5 Jt', 'trend' => '+8.1%'],
            ['label' => 'Retur', 'value' => '14 item', 'trend' => '-2.4%'],
            ['label' => 'Customer Baru', 'value' => '48 orang', 'trend' => '+9.7%'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($reports as $report)
                <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-stone-500">{{ $report['label'] }}</p>
                    <p class="mt-3 text-3xl font-black text-stone-900">{{ $report['value'] }}</p>
                    <p class="mt-2 text-xs font-semibold text-emerald-600">{{ $report['trend'] }} vs bulan lalu</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-bold text-stone-900">Ringkasan Kinerja</h3>
                <div class="mt-6 space-y-4">
                    <div>
                        <div class="mb-1 flex items-center justify-between text-sm text-stone-600">
                            <span>Penjualan</span>
                            <span>82%</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-stone-100">
                            <div class="h-2.5 rounded-full bg-amber-500" style="width: 82%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-1 flex items-center justify-between text-sm text-stone-600">
                            <span>Pelayanan</span>
                            <span>76%</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-stone-100">
                            <div class="h-2.5 rounded-full bg-emerald-500" style="width: 76%;"></div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-1 flex items-center justify-between text-sm text-stone-600">
                            <span>Stok</span>
                            <span>68%</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-stone-100">
                            <div class="h-2.5 rounded-full bg-sky-500" style="width: 68%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-bold text-stone-900">Laporan Terbaru</h3>
                <ul class="mt-6 space-y-4 text-sm text-stone-600">
                    <li class="flex items-start justify-between gap-3 rounded-2xl bg-stone-50 p-3">
                        <div>
                            <p class="font-semibold text-stone-900">Laporan penjualan mingguan</p>
                            <p class="text-stone-500">Diperbarui 2 jam lalu</p>
                        </div>
                        <span class="text-amber-600">Baru</span>
                    </li>
                    <li class="flex items-start justify-between gap-3 rounded-2xl bg-stone-50 p-3">
                        <div>
                            <p class="font-semibold text-stone-900">Analisis stok akhir bulan</p>
                            <p class="text-stone-500">Dibuat 1 hari lalu</p>
                        </div>
                        <span class="text-emerald-600">Ready</span>
                    </li>
                    <li class="flex items-start justify-between gap-3 rounded-2xl bg-stone-50 p-3">
                        <div>
                            <p class="font-semibold text-stone-900">Rekap customer aktif</p>
                            <p class="text-stone-500">Dibuat 3 hari lalu</p>
                        </div>
                        <span class="text-stone-600">Archive</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
