@extends('layouts.dashboard')

@section('title', 'Transaksi')
@section('page_kicker', 'Admin')
@section('page_title', 'Transaksi Penjualan')

@section('content')
    @php
        $transactions = [
            ['code' => 'TRX-1024', 'customer' => 'Rina Mardiana', 'amount' => 'Rp 1.350.000', 'method' => 'Transfer', 'status' => 'Selesai'],
            ['code' => 'TRX-1025', 'customer' => 'Andi Saputra', 'amount' => 'Rp 890.000', 'method' => 'COD', 'status' => 'Diproses'],
            ['code' => 'TRX-1026', 'customer' => 'Sari Dewi', 'amount' => 'Rp 640.000', 'method' => 'E-Wallet', 'status' => 'Selesai'],
            ['code' => 'TRX-1027', 'customer' => 'Dewi Lestari', 'amount' => 'Rp 2.140.000', 'method' => 'Transfer', 'status' => 'Menunggu'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Total Transaksi</p>
                <p class="mt-3 text-3xl font-black text-stone-900">1.284</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Pendapatan Hari Ini</p>
                <p class="mt-3 text-3xl font-black text-stone-900">Rp 24,8 Jt</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Menunggu Konfirmasi</p>
                <p class="mt-3 text-3xl font-black text-stone-900">16</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Pembayaran COD</p>
                <p class="mt-3 text-3xl font-black text-stone-900">38</p>
            </div>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-xl font-bold text-stone-900">Riwayat Transaksi</h3>
                <div class="flex gap-2 text-xs font-semibold">
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700">Selesai</span>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-amber-700">Diproses</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Kode</th>
                            <th class="pb-3 font-semibold">Customer</th>
                            <th class="pb-3 font-semibold">Metode</th>
                            <th class="pb-3 font-semibold">Jumlah</th>
                            <th class="pb-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-stone-900">{{ $transaction['code'] }}</td>
                                <td class="py-3 pr-4">{{ $transaction['customer'] }}</td>
                                <td class="py-3 pr-4">{{ $transaction['method'] }}</td>
                                <td class="py-3 pr-4">{{ $transaction['amount'] }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                        @if ($transaction['status'] === 'Selesai') bg-emerald-100 text-emerald-700
                                        @elseif ($transaction['status'] === 'Menunggu') bg-amber-100 text-amber-700
                                        @else bg-stone-100 text-stone-700
                                        @endif">
                                        {{ $transaction['status'] }}
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
