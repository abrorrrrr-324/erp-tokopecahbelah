@extends('layouts.dashboard')

@section('title', 'Detail Pesanan Pembelian')
@section('page_kicker', 'Supplier')
@section('page_title', 'Detail Pesanan Pembelian')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <section
            class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Kode pesanan</p>
                <h2 class="mt-1 text-2xl font-black text-stone-900">
                    PO-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</h2>
                <p class="mt-1 text-sm text-stone-500">Dibuat oleh {{ $order->user?->name ?? 'Admin' }}</p>
            </div>
            <a href="{{ route('supplier.pesanan') }}"
                class="rounded-xl border border-stone-200 px-4 py-2.5 text-center text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                Kembali ke daftar
            </a>
        </section>

        <section class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Tanggal pesanan</p>
                <p class="mt-1 font-bold text-stone-900">{{ $order->tanggal_pesanan?->translatedFormat('d M Y, H:i') }}</p>
            </div>
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Status</p>
                <span
                    class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $order->status === 'menunggu' ? 'bg-amber-100 text-amber-700' : ($order->status === 'dikonfirmasi' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'ditolak' ? 'bg-red-100 text-red-700' : ($order->status === 'dikirim' ? 'bg-blue-100 text-blue-700' : 'bg-stone-100 text-stone-700'))) }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </section>

        <section class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 text-xl font-bold text-stone-900">Rincian produk</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Produk</th>
                            <th class="pb-3 font-semibold">Jumlah</th>
                            <th class="pb-3 font-semibold">Ketersediaan stok</th>
                            <th class="pb-3 font-semibold">Harga</th>
                            <th class="pb-3 text-right font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4">
                                    <p class="font-semibold text-stone-900">{{ $item->product?->nama ?? 'Produk dihapus' }}
                                    </p>
                                    <p class="text-xs text-stone-500">{{ $item->product?->sku ?? '-' }}</p>
                                </td>
                                <td class="py-3 pr-4">{{ $item->jumlah }} {{ $item->product?->satuan }}</td>
                                <td class="py-3 pr-4">
                                    @if ((int) ($item->product?->stok ?? 0) > 0)
                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Tersedia</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-700">Tidak tersedia</span>
                                    @endif
                                    <p class="mt-1 text-xs text-stone-500">{{ number_format((int) ($item->product?->stok ?? 0), 0, ',', '.') }} {{ $item->product?->satuan }}</p>
                                </td>
                                <td class="py-3 pr-4">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                                <td class="py-3 text-right font-semibold text-stone-900">Rp
                                    {{ number_format((float) $item->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="pt-4 text-right font-bold text-stone-900">Total</td>
                            <td class="pt-4 text-right text-lg font-black text-stone-900">Rp
                                {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if ($order->catatan)
                <div class="mt-5 rounded-2xl bg-stone-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">Catatan admin</p>
                    <p class="mt-1 text-sm text-stone-700">{{ $order->catatan }}</p>
                </div>
            @endif
        </section>

        {{-- KONFIRMASI PESANAN --}}
        @if ($order->status === 'menunggu')
            <section
                class="flex flex-col gap-3 rounded-3xl border border-amber-200 bg-amber-50 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-bold text-stone-900">
                        {{ $hasUnavailableProducts ? 'Produk tidak tersedia' : 'Konfirmasi pesanan' }}</h3>
                    <p class="text-sm text-stone-600">
                        {{ $hasUnavailableProducts ? 'Tolak pesanan ini karena ada produk yang stoknya kosong.' : 'Pastikan rincian pesanan sudah sesuai sebelum mengonfirmasi.' }}
                    </p>
                </div>

                @if ($hasUnavailableProducts)
                    <form action="{{ route('supplier.pesanan.reject', $order) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-red-500 sm:w-auto">
                            Tolak pesanan
                        </button>
                    </form>
                @else
                    <form action="{{ route('supplier.pesanan.confirm', $order) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl bg-amber-500 px-5 py-3 text-sm font-bold text-stone-900 transition hover:bg-amber-400 sm:w-auto">
                            Konfirmasi pesanan
                        </button>
                    </form>
                @endif
            </section>
        @endif

        {{-- KIRIM BARANG --}}
        @if ($order->status === 'dikonfirmasi')
            <section
                class="flex flex-col gap-3 rounded-3xl border border-blue-200 bg-blue-50 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-bold text-stone-900">Pesanan siap dikirim</h3>
                    <p class="text-sm text-stone-600">
                        Pastikan barang sudah disiapkan. Setelah dikirim, Kepala Gudang dapat melakukan verifikasi
                        penerimaan barang.
                    </p>
                </div>

                <form action="{{ route('supplier.pesanan.ship', $order) }}" method="POST"
                    onsubmit="return confirm('Apakah barang untuk pesanan ini benar-benar sudah dikirim?')">
                    @csrf
                    <button type="submit"
                        class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-500 sm:w-auto">
                        Kirim Barang
                    </button>
                </form>
            </section>
        @elseif ($order->status === 'dikirim')
            <section class="rounded-3xl border border-blue-200 bg-blue-50 p-6">
                <h3 class="font-bold text-blue-900">Barang sudah dikirim</h3>
                <p class="mt-1 text-sm text-blue-700">
                    Pesanan sedang menunggu pemeriksaan dan verifikasi dari Kepala Gudang.
                </p>
            </section>
        @endif
    </div>
@endsection
