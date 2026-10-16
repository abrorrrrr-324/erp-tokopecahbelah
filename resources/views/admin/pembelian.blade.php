@extends('layouts.dashboard')

@section('title', 'Pembelian')
@section('page_kicker', 'Admin')
@section('page_title', 'Pembelian Barang')

@section('content')
    @php
        $orders = [
            ['code' => 'PO-1042', 'supplier' => 'PT Sentosa Jaya', 'date' => '15 Okt 2026', 'total' => 'Rp 8.420.000', 'status' => 'Diterima'],
            ['code' => 'PO-1043', 'supplier' => 'CV Mekar Textile', 'date' => '17 Okt 2026', 'total' => 'Rp 5.600.000', 'status' => 'Dalam Pengiriman'],
            ['code' => 'PO-1044', 'supplier' => 'UD Bumi Elektrik', 'date' => '18 Okt 2026', 'total' => 'Rp 3.980.000', 'status' => 'Menunggu'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Data pembelian stok dan kebutuhan supplier</p>
                <h2 class="mt-1 text-2xl font-bold text-stone-900">Purchase Order</h2>
            </div>
            <button type="button" class="rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400">
                + Buat PO Baru
            </button>
        </div>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">PO Bulan Ini</p>
                <p class="mt-3 text-3xl font-black text-stone-900">42</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Belum Diproses</p>
                <p class="mt-3 text-3xl font-black text-stone-900">7</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Nilai Pembelian</p>
                <p class="mt-3 text-3xl font-black text-stone-900">Rp 96 Jt</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Supplier Prioritas</p>
                <p class="mt-3 text-3xl font-black text-stone-900">5</p>
            </div>
        </section>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 text-xl font-bold text-stone-900">Daftar Pembelian</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Kode PO</th>
                            <th class="pb-3 font-semibold">Supplier</th>
                            <th class="pb-3 font-semibold">Tanggal</th>
                            <th class="pb-3 font-semibold">Total</th>
                            <th class="pb-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-stone-900">{{ $order['code'] }}</td>
                                <td class="py-3 pr-4">{{ $order['supplier'] }}</td>
                                <td class="py-3 pr-4">{{ $order['date'] }}</td>
                                <td class="py-3 pr-4">{{ $order['total'] }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                        @if ($order['status'] === 'Diterima') bg-emerald-100 text-emerald-700
                                        @elseif ($order['status'] === 'Dalam Pengiriman') bg-amber-100 text-amber-700
                                        @else bg-stone-100 text-stone-700
                                        @endif">
                                        {{ $order['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
