@extends('layouts.dashboard')

@section('title', 'Detail Purchase Order')
@section('page_kicker', 'Admin')
@section('page_title', 'Detail Purchase Order')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Kode pesanan</p>
                <h2 class="mt-1 text-2xl font-black text-stone-900">PO-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}</h2>
            </div>
            <a href="{{ route('admin.pembelian') }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-center text-sm font-semibold text-stone-700 hover:bg-stone-50">Kembali ke daftar</a>
        </section>

        <section class="grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Supplier</p>
                <p class="mt-1 font-bold text-stone-900">{{ $order->supplier?->nama ?? 'Supplier tidak tersedia' }}</p>
                <p class="text-sm text-stone-500">{{ $order->supplier?->user?->email ?? '-' }}</p>
            </div>
            <div class="rounded-2xl border border-stone-200 bg-white p-5">
                <p class="text-sm text-stone-500">Tanggal pesanan</p>
                <p class="mt-1 font-bold text-stone-900">{{ $order->tanggal_pesanan?->translatedFormat('d M Y, H:i') }}</p>
                <p class="mt-3 text-sm text-stone-500">Status supplier</p>
                <span class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">{{ ucfirst($order->status) }}</span>
                <p class="mt-3 text-sm text-stone-500">Pembayaran</p>
                <p class="mt-1 font-semibold text-stone-900">
                    {{ $order->metode_pembayaran ? strtoupper($order->metode_pembayaran) : 'Belum dipilih' }}
                    · {{ ucfirst(str_replace('_', ' ', $order->status_pembayaran)) }}
                </p>
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
                            <th class="pb-3 font-semibold">Harga</th>
                            <th class="pb-3 text-right font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4">
                                    <p class="font-semibold text-stone-900">{{ $item->product?->nama ?? 'Produk dihapus' }}</p>
                                    <p class="text-xs text-stone-500">{{ $item->product?->sku ?? '-' }}</p>
                                </td>
                                <td class="py-3 pr-4">{{ $item->jumlah }} {{ $item->product?->satuan }}</td>
                                <td class="py-3 pr-4">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</td>
                                <td class="py-3 text-right font-semibold text-stone-900">Rp {{ number_format((float) $item->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="pt-4 text-right font-bold text-stone-900">Total</td>
                            <td class="pt-4 text-right text-lg font-black text-stone-900">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @if ($order->catatan)
                <div class="mt-5 rounded-2xl bg-stone-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-stone-500">Catatan</p>
                    <p class="mt-1 text-sm text-stone-700">{{ $order->catatan }}</p>
                </div>
            @endif
        </section>

        @if ($order->status_pembayaran === 'belum_dibayar')
            <section class="space-y-5 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <div>
                    <h3 class="text-xl font-bold text-stone-900">Pilih metode pembayaran</h3>
                    <p class="mt-1 text-sm text-stone-500">Pilih metode pembayaran, lalu tekan Checkout untuk melanjutkan.</p>
                </div>
                <form action="{{ route('admin.pembelian.checkout', $order) }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                    @csrf
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="cash" required class="peer sr-only">
                        <span class="block rounded-2xl border border-stone-200 p-5 transition hover:border-amber-400 hover:bg-amber-50 peer-checked:border-amber-500 peer-checked:bg-amber-50">
                            <span class="block text-lg font-bold text-stone-900">Cash</span>
                            <span class="mt-1 block text-sm text-stone-500">Checkout langsung dengan pembayaran cash.</span>
                        </span>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="payment_method" value="qris" required class="peer sr-only">
                        <span class="block rounded-2xl border border-stone-200 p-5 transition hover:border-amber-400 hover:bg-amber-50 peer-checked:border-amber-500 peer-checked:bg-amber-50">
                            <span class="block text-lg font-bold text-stone-900">QRIS</span>
                            <span class="mt-1 block text-sm text-stone-500">Tampilkan QRIS simulasi sesuai total pesanan.</span>
                        </span>
                    </label>
                    <div class="sm:col-span-2 sm:text-right">
                        <button type="submit" class="w-full rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-stone-900 transition hover:bg-amber-400 sm:w-auto">
                            Checkout
                        </button>
                    </div>
                </form>
            </section>
        @elseif ($order->metode_pembayaran === 'qris' && $order->status_pembayaran === 'menunggu')
            <section class="flex flex-col items-center gap-5 rounded-3xl border border-amber-200 bg-white p-6 text-center shadow-sm">
                <div>
                    <h3 class="text-xl font-bold text-stone-900">Pembayaran QRIS</h3>
                    <p class="mt-1 text-sm text-stone-500">Tampilan QR untuk demo pembayaran pesanan ini.</p>
                </div>
                <div class="flex h-56 w-56 items-center justify-center rounded-2xl border-8 border-stone-900 bg-white p-4" role="img" aria-label="QRIS simulasi untuk pesanan {{ $order->id }}, bukan kode pembayaran sungguhan">
                    <svg viewBox="0 0 21 21" class="h-full w-full" aria-hidden="true" shape-rendering="crispEdges">
                        <rect width="21" height="21" fill="white" />
                        <path fill="#171717" d="M0 0h7v7H0zM1 1v5h5V1zM2 2h3v3H2zM14 0h7v7h-7zM15 1v5h5V1zM16 2h3v3h-3zM0 14h7v7H0zM1 15v5h5v-5zM2 16h3v3H2zM8 0h2v2H8zM10 3h2v2h-2zM8 6h2v2H8zM11 7h3v2h-3zM8 9h2v2H8zM12 10h2v2h-2zM15 8h2v2h-2zM18 8h3v2h-3zM7 11h3v2H7zM10 13h2v2h-2zM13 13h2v2h-2zM16 12h2v3h-2zM8 15h2v2H8zM11 16h3v2h-3zM15 17h2v2h-2zM19 11h2v3h-2zM18 15h3v2h-3zM8 19h2v2H8zM12 19h2v2h-2zM17 19h2v2h-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-stone-500">Total pembayaran</p>
                    <p class="text-2xl font-black text-stone-900">Rp {{ number_format((float) $order->total, 2, ',', '.') }}</p>
                </div>
                <p class="max-w-lg rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    QRIS ini hanya simulasi untuk demo, tidak dapat dipindai atau digunakan untuk transaksi sungguhan.
                </p>
                <form action="{{ route('admin.pembelian.qris.pay', $order) }}" method="POST">
                    @csrf
                    <button type="submit" class="rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-stone-900 transition hover:bg-amber-400">
                        Bayar
                    </button>
                </form>
            </section>
        @endif
    </div>
@endsection
