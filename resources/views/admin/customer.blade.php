@extends('layouts.dashboard')

@section('title', 'Customer')
@section('page_kicker', 'Admin')
@section('page_title', 'Data Customer')

@section('content')
    @php
        $customers = [
            ['name' => 'Rina Mardiana', 'email' => 'rina@mail.com', 'orders' => 12, 'total' => 'Rp 4.250.000', 'status' => 'Aktif'],
            ['name' => 'Andi Saputra', 'email' => 'andi@mail.com', 'orders' => 7, 'total' => 'Rp 2.980.000', 'status' => 'Aktif'],
            ['name' => 'Sari Dewi', 'email' => 'sari@mail.com', 'orders' => 4, 'total' => 'Rp 1.460.000', 'status' => 'Baru'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@mail.com', 'orders' => 19, 'total' => 'Rp 8.100.000', 'status' => 'VIP'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Total Customer</p>
                <p class="mt-3 text-3xl font-black text-stone-900">312</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Customer Aktif</p>
                <p class="mt-3 text-3xl font-black text-stone-900">246</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Customer Baru</p>
                <p class="mt-3 text-3xl font-black text-stone-900">34</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Nilai Transaksi</p>
                <p class="mt-3 text-3xl font-black text-stone-900">Rp 82 Jt</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-stone-900">Customer Terbaru</h3>
                    <button type="button" class="rounded-lg border border-stone-200 px-3 py-1.5 text-sm font-medium text-stone-700 hover:bg-stone-50">
                        Export
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm text-stone-600">
                        <thead>
                            <tr class="border-b border-stone-200 text-stone-500">
                                <th class="pb-3 font-semibold">Nama</th>
                                <th class="pb-3 font-semibold">Email</th>
                                <th class="pb-3 font-semibold">Order</th>
                                <th class="pb-3 font-semibold">Total</th>
                                <th class="pb-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr class="border-b border-stone-100 last:border-0">
                                    <td class="py-3 pr-4 font-semibold text-stone-900">{{ $customer['name'] }}</td>
                                    <td class="py-3 pr-4">{{ $customer['email'] }}</td>
                                    <td class="py-3 pr-4">{{ $customer['orders'] }}</td>
                                    <td class="py-3 pr-4">{{ $customer['total'] }}</td>
                                    <td class="py-3 pr-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                            @if ($customer['status'] === 'VIP') bg-amber-100 text-amber-700
                                            @elseif ($customer['status'] === 'Baru') bg-emerald-100 text-emerald-700
                                            @else bg-stone-100 text-stone-700
                                            @endif">
                                            {{ $customer['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-bold text-stone-900">Tambah Customer</h3>
                <form class="mt-5 space-y-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-stone-700">Nama lengkap</label>
                        <input type="text" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" placeholder="Masukkan nama">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-stone-700">Email</label>
                        <input type="email" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" placeholder="customer@email.com">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-stone-700">Nomor HP</label>
                        <input type="text" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" placeholder="08xxx">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400">
                        Simpan Customer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
