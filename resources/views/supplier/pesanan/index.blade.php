@extends('layouts.dashboard')

@section('title', 'Pesanan Pembelian')
@section('page_kicker', 'Supplier')
@section('page_title', 'Pesanan Pembelian')

@section('content')
    <section class="space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-stone-900">Daftar Pesanan</h2>
                    <p class="mt-1 text-sm text-stone-500">Tinjau pesanan pembelian dan lakukan aksi sesuai statusnya.</p>
                </div>
                <p class="text-sm font-semibold text-stone-600">{{ $orders->total() }} pesanan</p>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                            <th class="px-4 py-3 font-semibold">No. Pesanan</th>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold">Produk</th>
                            <th class="px-4 py-3 text-right font-semibold">Total</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($orders as $order)
                            <tr class="align-top transition hover:bg-stone-50">
                                <td class="whitespace-nowrap px-4 py-4">
                                    <p class="font-bold text-stone-900">
                                        PO-{{ str_pad((string) $order->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                    <p class="mt-1 text-xs text-stone-500">
                                        Dibuat oleh {{ $order->user?->name ?? 'Admin' }}
                                    </p>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-stone-600">
                                    {{ $order->tanggal_pesanan?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="space-y-1">
                                        @foreach ($order->items as $item)
                                            <p class="text-stone-700">
                                                {{ $item->product?->nama ?? 'Produk dihapus' }}
                                                <span class="whitespace-nowrap text-stone-500">
                                                    ({{ $item->jumlah }} {{ $item->product?->satuan }})
                                                </span>
                                            </p>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right font-semibold text-stone-900">
                                    Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span
                                        class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ match ($order->status) {
                                            'menunggu' => 'bg-amber-100 text-amber-700',
                                            'dikonfirmasi' => 'bg-emerald-100 text-emerald-700',
                                            'dikirim' => 'bg-blue-100 text-blue-700',
                                            'ditolak' => 'bg-red-100 text-red-700',
                                            default => 'bg-stone-100 text-stone-700',
                                        } }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        <a
                                            href="{{ route('supplier.pesanan.show', $order) }}"
                                            class="rounded-lg border border-stone-200 px-3 py-2 text-xs font-semibold text-stone-700 transition hover:bg-stone-100"
                                        >
                                            Detail
                                        </a>

                                        @if ($order->status === 'menunggu')
                                            <form action="{{ route('supplier.pesanan.confirm', $order) }}" method="POST">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-bold text-stone-900 transition hover:bg-amber-400"
                                                >
                                                    Konfirmasi
                                                </button>
                                            </form>
                                        @elseif ($order->status === 'dikonfirmasi')
                                            <form
                                                action="{{ route('supplier.pesanan.ship', $order) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah barang untuk pesanan ini benar-benar sudah dikirim?')"
                                            >
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-bold text-white transition hover:bg-blue-500"
                                                >
                                                    Kirim
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-stone-500">
                                    Belum ada pesanan pembelian untuk supplier Anda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $orders->links() }}</div>
    </section>
@endsection
