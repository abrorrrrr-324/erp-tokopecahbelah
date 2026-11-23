@extends('layouts.dashboard')

@section('title', 'Detail Audit Barang')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Detail Audit Barang')

@section('content')
    <section class="mt-8 grid gap-6 xl:grid-cols-[1fr_0.8fr]">
        <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="text-sm text-stone-500">Audit #{{ $audit->id }}</p>
                    <h2 class="mt-1 text-2xl font-bold text-stone-900">{{ $audit->produk?->nama ?? 'Produk dihapus' }}</h2>
                </div>
                <span class="rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">{{ str_replace('_', ' ', ucfirst($audit->status)) }}</span>
            </div>

            <dl class="mt-6 grid gap-4 border-t border-stone-100 pt-5 sm:grid-cols-2">
                <div><dt class="text-xs text-stone-500">Jenis</dt><dd class="mt-1 font-semibold capitalize">{{ $audit->jenis }}</dd></div>
                <div><dt class="text-xs text-stone-500">Jumlah</dt><dd class="mt-1 font-semibold">{{ number_format($audit->jumlah, 0, ',', '.') }} {{ $audit->produk?->satuan ?? 'unit' }}</dd></div>
                <div><dt class="text-xs text-stone-500">Penyebab</dt><dd class="mt-1 font-semibold">{{ $audit->penyebab }}</dd></div>
                <div><dt class="text-xs text-stone-500">Tanggal</dt><dd class="mt-1 font-semibold">{{ $audit->created_at?->format('d/m/Y H:i') }}</dd></div>
                <div><dt class="text-xs text-stone-500">Petugas</dt><dd class="mt-1 font-semibold">{{ $audit->petugas?->name ?? '-' }}</dd></div>
                <div><dt class="text-xs text-stone-500">Catatan</dt><dd class="mt-1 font-semibold">{{ $audit->catatan ?: '-' }}</dd></div>
                <div><dt class="text-xs text-stone-500">Ditindaklanjuti oleh</dt><dd class="mt-1 font-semibold">{{ $audit->reviewedBy?->name ?? '-' }}</dd></div>
                <div><dt class="text-xs text-stone-500">Tindak lanjut</dt><dd class="mt-1 font-semibold">{{ $audit->tindak_lanjut ?: '-' }}</dd></div>
            </dl>

            @if ($audit->foto_bukti)
                <div class="mt-6 border-t border-stone-100 pt-5">
                    <p class="mb-2 text-sm font-semibold text-stone-700">Foto Bukti</p>
                    <a href="{{ route('gudang.audit.foto', $audit) }}" target="_blank" rel="noopener">
                        <img src="{{ route('gudang.audit.foto', $audit) }}" alt="Bukti audit barang" class="max-h-80 rounded-xl border border-stone-200 object-contain">
                    </a>
                </div>
            @endif
        </article>

        @if ($audit->status === 'menunggu_pemeriksaan')
            <form action="{{ route('gudang.audit.review', $audit) }}" method="POST" class="h-fit rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                @csrf
                <h2 class="text-lg font-bold text-stone-900">Pemeriksaan dan Tindak Lanjut</h2>
                <p class="mt-1 text-sm text-stone-500">Stok akan berkurang hanya jika audit disetujui.</p>
                <label for="tindak_lanjut" class="mb-2 mt-5 block text-sm font-semibold text-stone-700">Catatan Tindak Lanjut</label>
                <textarea id="tindak_lanjut" name="tindak_lanjut" rows="4" required class="w-full rounded-xl border border-stone-200 px-4 py-3 text-sm">{{ old('tindak_lanjut') }}</textarea>
                @error('tindak_lanjut') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                <div class="mt-5 flex flex-wrap justify-end gap-3">
                    <button name="keputusan" value="ditolak" class="rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-700">Tolak Audit</button>
                    <button name="keputusan" value="disetujui" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white">Setujui dan Kurangi Stok</button>
                </div>
            </form>
        @endif
    </section>
    <a href="{{ route('gudang.audit') }}" class="mt-6 inline-flex rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-700 hover:bg-stone-50">Kembali ke Riwayat Audit</a>
@endsection
