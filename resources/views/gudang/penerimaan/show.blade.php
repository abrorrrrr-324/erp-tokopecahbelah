
@extends('layouts.dashboard')

@section('title', 'Pemeriksaan Barang')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Pemeriksaan Barang')

@section('content')
    <section class="mt-8 space-y-6">

        {{-- INFORMASI PESANAN --}}
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-500">Nomor Pesanan</p>
                    <h2 class="mt-1 text-2xl font-bold text-stone-900">
                        PO-{{ str_pad($purchaseOrder->id, 5, '0', STR_PAD_LEFT) }}
                    </h2>
                </div>

                <span class="w-fit rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">
                    Menunggu Pemeriksaan
                </span>
            </div>

            <div class="mt-6 grid gap-5 border-t border-stone-100 pt-5 sm:grid-cols-3">
                <div>
                    <p class="text-xs text-stone-500">Supplier</p>
                    <p class="mt-1 font-semibold text-stone-800">
                        {{ $purchaseOrder->supplier->nama ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-stone-500">Tanggal Pesanan</p>
                    <p class="mt-1 font-semibold text-stone-800">
                        {{ $purchaseOrder->tanggal_pesanan
                            ? \Carbon\Carbon::parse($purchaseOrder->tanggal_pesanan)->format('d F Y')
                            : '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-stone-500">Total Pembelian</p>
                    <p class="mt-1 font-semibold text-stone-800">
                        Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- DAFTAR BARANG --}}
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-stone-900">
                    Pemeriksaan Produk
                </h2>
                <p class="mt-1 text-sm text-stone-500">
                    Periksa jumlah dan kondisi setiap produk yang diterima.
                </p>
            </div>

            <form
                action="{{ route('gudang.penerimaan.verifikasi', $purchaseOrder) }}"
                method="POST"
            >
                @csrf

                <div class="space-y-5">
                    @foreach ($purchaseOrder->items as $item)
                        <article class="rounded-2xl border border-stone-200 bg-stone-50 p-5">

                            <div class="mb-4">
                                <p class="text-xs font-medium uppercase tracking-wider text-stone-500">
                                    SKU: {{ $item->product->sku ?? '-' }}
                                </p>

                                <h3 class="mt-1 text-lg font-bold text-stone-900">
                                    {{ $item->product->nama ?? 'Produk tidak ditemukan' }}
                                </h3>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-stone-700">
                                        Jumlah Pesanan
                                    </label>

                                    <div class="rounded-xl border border-stone-200 bg-white px-4 py-3 font-bold text-stone-800">
                                        {{ $item->jumlah }} unit
                                    </div>
                                </div>

                                <div>
                                    <label
                                        for="jumlah_{{ $item->produk_id }}"
                                        class="mb-2 block text-sm font-semibold text-stone-700"
                                    >
                                        Jumlah Diterima
                                    </label>

                                    <input
                                        type="number"
                                        id="jumlah_{{ $item->produk_id }}"
                                        name="items[{{ $item->produk_id }}][jumlah]"
                                        min="0"
                                        value="{{ old('items.' . $item->produk_id . '.jumlah', $item->jumlah) }}"
                                        required
                                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-800 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                    >
                                </div>
                            </div>

                            <div class="mt-4">
                                <label
                                    for="catatan_{{ $item->produk_id }}"
                                    class="mb-2 block text-sm font-semibold text-stone-700"
                                >
                                    Catatan Kondisi Barang
                                </label>

                                <textarea
                                    id="catatan_{{ $item->produk_id }}"
                                    name="items[{{ $item->produk_id }}][catatan]"
                                    rows="2"
                                    placeholder="Contoh: Barang dalam kondisi baik atau terdapat kerusakan..."
                                    class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-800 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                >{{ old('items.' . $item->produk_id . '.catatan') }}</textarea>
                            </div>

                        </article>
                    @endforeach
                </div>

                {{-- KEPUTUSAN PEMERIKSAAN --}}
                <div class="mt-6 rounded-2xl border border-stone-200 p-5">
                    <h3 class="text-lg font-bold text-stone-900">
                        Hasil Pemeriksaan
                    </h3>

                    <p class="mt-1 text-sm text-stone-500">
                        Pilih keputusan berdasarkan hasil pemeriksaan seluruh barang.
                    </p>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">

                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                            <input
                                type="radio"
                                name="keputusan"
                                value="diterima"
                                {{ old('keputusan') === 'diterima' ? 'checked' : '' }}
                                required
                                class="mt-1 accent-emerald-600"
                            >

                            <span>
                                <strong class="block text-emerald-800">
                                    Barang Sesuai
                                </strong>
                                <span class="mt-1 block text-sm text-emerald-700">
                                    Terima barang dan tambahkan stok setelah verifikasi berhasil.
                                </span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4">
                            <input
                                type="radio"
                                name="keputusan"
                                value="ditolak"
                                {{ old('keputusan') === 'ditolak' ? 'checked' : '' }}
                                required
                                class="mt-1 accent-red-600"
                            >

                            <span>
                                <strong class="block text-red-800">
                                    Barang Tidak Sesuai
                                </strong>
                                <span class="mt-1 block text-sm text-red-700">
                                    Tolak penerimaan dan jangan tambahkan stok.
                                </span>
                            </span>
                        </label>

                    </div>

                    <div class="mt-5">
                        <label for="catatan" class="mb-2 block text-sm font-semibold text-stone-700">
                            Catatan Pemeriksaan Keseluruhan
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="3"
                            placeholder="Tuliskan alasan penolakan atau keterangan pemeriksaan..."
                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-800 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                        >{{ old('catatan') }}</textarea>
                    </div>
                </div>

                {{-- TOMBOL --}}
                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                    <a
                        href="{{ route('gudang.penerimaan') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-stone-200 px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-100"
                    >
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-stone-900 transition hover:bg-amber-400"
                    >
                        Simpan Hasil Pemeriksaan
                    </button>
                </div>

            </form>
        </div>

    </section>
@endsection
