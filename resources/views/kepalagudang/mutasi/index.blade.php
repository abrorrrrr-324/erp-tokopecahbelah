@extends('layouts.dashboard')

@section('title', 'Riwayat Mutasi Stok')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Riwayat Mutasi Stok')

@section('content')
    <section class="mt-8 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Riwayat Perubahan Stok</h2>
                <p class="text-sm text-stone-500">Seluruh transaksi masuk, keluar, rusak, dan hilang.</p>
            </div>
            <a href="{{ route('gudang.stok.mutasi.create') }}" class="rounded-xl bg-amber-500 px-5 py-3 text-center text-sm font-bold text-stone-900 hover:bg-amber-400">
                Catat Mutasi
            </a>
        </div>

        <form method="GET" action="{{ route('gudang.stok.mutasi') }}" class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <select name="produk_id" class="rounded-xl border border-stone-200 bg-white px-3 py-3 text-sm">
                <option value="">Semua produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" @selected(($filters['produk_id'] ?? '') == $product->id)>{{ $product->nama }}</option>
                @endforeach
            </select>
            <select name="jenis" class="rounded-xl border border-stone-200 bg-white px-3 py-3 text-sm">
                <option value="">Semua jenis</option>
                @foreach (['masuk' => 'Masuk', 'keluar' => 'Keluar', 'rusak' => 'Rusak', 'hilang' => 'Hilang'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['jenis'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="rounded-xl border border-stone-200 px-3 py-3 text-sm" aria-label="Tanggal mulai">
            <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="rounded-xl border border-stone-200 px-3 py-3 text-sm" aria-label="Tanggal selesai">
            <div class="flex gap-2">
                <button class="flex-1 rounded-xl bg-stone-900 px-4 py-3 text-sm font-semibold text-white">Filter</button>
                <a href="{{ route('gudang.stok.mutasi') }}" class="rounded-xl border border-stone-200 px-4 py-3 text-sm font-semibold text-stone-600">Reset</a>
            </div>
        </form>

        @if ($movements->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-4 py-10 text-center text-sm text-stone-500">Belum ada mutasi stok.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Sumber</th>
                            <th class="px-4 py-3">Petugas</th>
                            <th class="px-4 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($movements as $movement)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4 text-stone-600">{{ $movement->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-stone-900">{{ $movement->produk?->nama ?? 'Produk dihapus' }}</p>
                                    <p class="text-xs text-stone-500">{{ $movement->produk?->sku ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4 capitalize">{{ str_replace('_', ' ', $movement->jenis) }}</td>
                                <td class="whitespace-nowrap px-4 py-4 font-semibold">{{ number_format($movement->jumlah, 0, ',', '.') }} {{ $movement->produk?->satuan ?? 'unit' }}</td>
                                <td class="px-4 py-4">{{ str_replace('_', ' ', $movement->referensi_tipe ?? 'manual') }}</td>
                                <td class="px-4 py-4">{{ $movement->user?->name ?? '-' }}</td>
                                <td class="max-w-xs px-4 py-4 text-stone-600">{{ $movement->catatan ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">{{ $movements->links() }}</div>
        @endif
    </section>
@endsection
