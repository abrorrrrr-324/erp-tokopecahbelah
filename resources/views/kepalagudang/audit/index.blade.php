@extends('layouts.dashboard')

@section('title', 'Audit Kerusakan dan Kehilangan')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Audit Kerusakan / Kehilangan')

@section('content')
    <section class="mt-8 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Riwayat Audit Barang</h2>
                <p class="text-sm text-stone-500">Catatan barang rusak atau hilang beserta pemeriksaan dan tindak lanjut.</p>
            </div>
            <a href="{{ route('gudang.audit.create') }}" class="rounded-xl bg-amber-500 px-5 py-3 text-center text-sm font-bold text-stone-900 hover:bg-amber-400">Catat Audit</a>
        </div>

        <form method="GET" action="{{ route('gudang.audit') }}" class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <select name="jenis" class="rounded-xl border border-stone-200 px-3 py-3 text-sm">
                <option value="">Rusak dan hilang</option>
                <option value="rusak" @selected(($filters['jenis'] ?? '') === 'rusak')>Rusak</option>
                <option value="hilang" @selected(($filters['jenis'] ?? '') === 'hilang')>Hilang</option>
            </select>
            <select name="status" class="rounded-xl border border-stone-200 px-3 py-3 text-sm">
                <option value="">Semua status</option>
                <option value="menunggu_pemeriksaan" @selected(($filters['status'] ?? '') === 'menunggu_pemeriksaan')>Menunggu pemeriksaan</option>
                <option value="disetujui" @selected(($filters['status'] ?? '') === 'disetujui')>Disetujui</option>
                <option value="ditolak" @selected(($filters['status'] ?? '') === 'ditolak')>Ditolak</option>
            </select>
            <input type="date" name="tanggal_mulai" value="{{ $filters['tanggal_mulai'] ?? '' }}" class="rounded-xl border border-stone-200 px-3 py-3 text-sm" aria-label="Tanggal mulai">
            <input type="date" name="tanggal_selesai" value="{{ $filters['tanggal_selesai'] ?? '' }}" class="rounded-xl border border-stone-200 px-3 py-3 text-sm" aria-label="Tanggal selesai">
            <div class="flex gap-2">
                <button class="flex-1 rounded-xl bg-stone-900 px-4 py-3 text-sm font-semibold text-white">Filter</button>
                <a href="{{ route('gudang.audit') }}" class="rounded-xl border border-stone-200 px-4 py-3 text-sm font-semibold text-stone-600">Reset</a>
            </div>
        </form>

        @if ($audits->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-4 py-10 text-center text-sm text-stone-500">Belum ada catatan audit.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                        <tr>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Jenis</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Petugas</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($audits as $audit)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4">{{ $audit->created_at?->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-4">
                                    <p class="font-semibold">{{ $audit->produk?->nama ?? 'Produk dihapus' }}</p>
                                    <p class="text-xs text-stone-500">{{ $audit->produk?->sku ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-4 capitalize">{{ $audit->jenis }}</td>
                                <td class="px-4 py-4">{{ number_format($audit->jumlah, 0, ',', '.') }} {{ $audit->produk?->satuan ?? 'unit' }}</td>
                                <td class="px-4 py-4">{{ $audit->petugas?->name ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    @php
                                        $statusClasses = match ($audit->status) {
                                            'disetujui' => 'bg-emerald-100 text-emerald-700',
                                            'ditolak' => 'bg-red-100 text-red-700',
                                            default => 'bg-amber-100 text-amber-800',
                                        };
                                    @endphp
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">{{ str_replace('_', ' ', ucfirst($audit->status)) }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('gudang.audit.show', $audit) }}" class="rounded-lg border border-stone-200 px-3 py-2 text-xs font-semibold hover:bg-stone-50">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">{{ $audits->links() }}</div>
        @endif
    </section>
@endsection
