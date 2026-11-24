
@extends('layouts.dashboard')

@section('title', 'Penerimaan Barang')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Penerimaan Barang')

@section('content')
    <section class="mt-8 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-stone-900">
                    Daftar Pesanan Masuk
                </h2>
                <p class="text-sm text-stone-500">
                    Pesanan dari Supplier yang sudah dikirim dan menunggu pemeriksaan.
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-2 rounded-xl bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-800">
                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                {{ $pesanan->count() }} Pesanan Menunggu
            </div>
        </div>

        {{-- DAFTAR PESANAN --}}
        @if ($pesanan->isEmpty())

            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-4 py-12 text-center">
                <div class="mb-3 text-4xl">📦</div>

                <h3 class="text-lg font-bold text-stone-800">
                    Belum Ada Barang Masuk
                </h3>

                <p class="mt-2 text-sm text-stone-500">
                    Pesanan yang sudah dikirim Supplier akan muncul di halaman ini.
                </p>
            </div>

        @else

            <div class="overflow-x-auto">
                <table class="w-full min-w-[750px] text-left">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                            <th class="px-4 py-4 font-semibold">No. Pesanan</th>
                            <th class="px-4 py-4 font-semibold">Supplier</th>
                            <th class="px-4 py-4 font-semibold">Tanggal Pesanan</th>
                            <th class="px-4 py-4 font-semibold">Jumlah Produk</th>
                            <th class="px-4 py-4 font-semibold">Total Pembelian</th>
                            <th class="px-4 py-4 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-100">

                        @foreach ($pesanan as $po)
                            <tr class="transition hover:bg-stone-50">

                                <td class="px-4 py-4">
                                    <span class="font-bold text-stone-900">
                                        PO-{{ str_pad($po->id, 5, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-semibold text-stone-800">
                                        {{ $po->supplier->nama ?? 'Supplier tidak ditemukan' }}
                                    </p>
                                </td>

                                <td class="px-4 py-4 text-sm text-stone-600">
                                    {{ $po->tanggal_pesanan
                                        ? \Carbon\Carbon::parse($po->tanggal_pesanan)->format('d/m/Y')
                                        : '-' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-stone-700">
                                    {{ $po->items->count() }} jenis produk
                                </td>

                                <td class="px-4 py-4 text-sm font-semibold text-stone-800">
                                    Rp {{ number_format($po->total, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-4 text-center">
                                    <a
                                        href="{{ route('gudang.penerimaan.show', $po) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                                        Periksa Barang
                                    </a>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        @endif

    </section>
@endsection
