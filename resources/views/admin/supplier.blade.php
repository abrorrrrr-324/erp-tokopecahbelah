@extends('layouts.dashboard')

@section('title', 'Supplier')
@section('page_kicker', 'Admin')
@section('page_title', 'Data Supplier')

@section('content')
    @php
        $suppliers = [
            ['name' => 'PT Sentosa Jaya', 'phone' => '0811-2233-990', 'items' => 'Furniture', 'lead_time' => '4 hari', 'status' => 'Aktif'],
            ['name' => 'CV Mekar Textile', 'phone' => '0822-4455-770', 'items' => 'Textil', 'lead_time' => '3 hari', 'status' => 'Aktif'],
            ['name' => 'UD Bumi Elektrik', 'phone' => '0857-9910-221', 'items' => 'Elektronik', 'lead_time' => '6 hari', 'status' => 'Review'],
            ['name' => 'CV Tiga Pilar', 'phone' => '0812-5567-119', 'items' => 'Peralatan', 'lead_time' => '5 hari', 'status' => 'Aktif'],
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Total Supplier</p>
                <p class="mt-3 text-3xl font-black text-stone-900">14</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Aktif</p>
                <p class="mt-3 text-3xl font-black text-stone-900">11</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Review</p>
                <p class="mt-3 text-3xl font-black text-stone-900">2</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Rata-rata Lead Time</p>
                <p class="mt-3 text-3xl font-black text-stone-900">4.6 hari</p>
            </div>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-xl font-bold text-stone-900">Daftar Mitra Supplier</h3>
                <button type="button" class="rounded-xl bg-stone-900 px-4 py-2 text-sm font-semibold text-white hover:bg-stone-800">
                    + Tambah Supplier
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Nama</th>
                            <th class="pb-3 font-semibold">Kontak</th>
                            <th class="pb-3 font-semibold">Kategori</th>
                            <th class="pb-3 font-semibold">Lead Time</th>
                            <th class="pb-3 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-stone-900">{{ $supplier['name'] }}</td>
                                <td class="py-3 pr-4">{{ $supplier['phone'] }}</td>
                                <td class="py-3 pr-4">{{ $supplier['items'] }}</td>
                                <td class="py-3 pr-4">{{ $supplier['lead_time'] }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                        @if ($supplier['status'] === 'Review') bg-amber-100 text-amber-700
                                        @else bg-emerald-100 text-emerald-700
                                        @endif">
                                        {{ $supplier['status'] }}
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
