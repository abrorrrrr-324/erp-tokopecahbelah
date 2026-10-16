@extends('layouts.dashboard')

@section('title', 'Stok')
@section('page_kicker', 'Admin')
@section('page_title', 'Kelola Stok')

@section('content')
    @php
        $stockItems = [
            ['name' => 'Kursi Kayu Minimalis', 'sku' => 'FK-1002', 'stock' => 32, 'reserved' => 6, 'status' => 'Aman'],
            ['name' => 'Lampu LED 12W', 'sku' => 'EL-2207', 'stock' => 18, 'reserved' => 4, 'status' => 'Hampir habis'],
            ['name' => 'Sprei Queen Set', 'sku' => 'TX-3321', 'stock' => 9, 'reserved' => 2, 'status' => 'Hampir habis'],
            ['name' => 'Lemari 3 Pintu', 'sku' => 'FK-4011', 'stock' => 14, 'reserved' => 3, 'status' => 'Aman'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Total Item</p>
                <p class="mt-3 text-3xl font-black text-stone-900">328</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Stok Aman</p>
                <p class="mt-3 text-3xl font-black text-stone-900">294</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Hampir Habis</p>
                <p class="mt-3 text-3xl font-black text-stone-900">22</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Barang Keluar</p>
                <p class="mt-3 text-3xl font-black text-stone-900">146</p>
            </div>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-xl font-bold text-stone-900">Status Stok</h3>
                <button type="button" class="rounded-xl border border-stone-200 px-3 py-1.5 text-sm font-medium text-stone-700 hover:bg-stone-50">
                    Refresh
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Produk</th>
                            <th class="pb-3 font-semibold">SKU</th>
                            <th class="pb-3 font-semibold">Stok</th>
                            <th class="pb-3 font-semibold">Reserve</th>
                            <th class="pb-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stockItems as $item)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-stone-900">{{ $item['name'] }}</td>
                                <td class="py-3 pr-4">{{ $item['sku'] }}</td>
                                <td class="py-3 pr-4">{{ $item['stock'] }}</td>
                                <td class="py-3 pr-4">{{ $item['reserved'] }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                        @if ($item['status'] === 'Hampir habis') bg-amber-100 text-amber-700
                                        @else bg-emerald-100 text-emerald-700
                                        @endif">
                                        {{ $item['status'] }}
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
