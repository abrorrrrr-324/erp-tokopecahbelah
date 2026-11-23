@extends('layouts.dashboard')

@section('title', 'Pemeriksaan Barang')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Pemeriksaan Barang')

@section('content')
    @php
        $receipt = $purchaseOrder->penerimaanBarang;
        $isWaiting = ! $receipt || $receipt->status === 'menunggu';
        $needsFollowUp = $receipt?->status === 'perlu_pemeriksaan';
        $statusLabels = [
            'menunggu' => 'Menunggu Pemeriksaan',
            'perlu_pemeriksaan' => 'Perlu Pemeriksaan',
            'disetujui' => 'Disetujui',
            'diterima' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];
        $status = $receipt?->status ?? 'menunggu';
    @endphp

    <section class="mt-8 space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-stone-500">Nomor Pesanan</p>
                    <h2 class="mt-1 text-2xl font-bold text-stone-900">
                        PO-{{ str_pad($purchaseOrder->id, 5, '0', STR_PAD_LEFT) }}
                    </h2>
                </div>

                <span class="w-fit rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">
                    {{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}
                </span>
            </div>

            <div class="mt-6 grid gap-5 border-t border-stone-100 pt-5 sm:grid-cols-3">
                <div>
                    <p class="text-xs text-stone-500">Supplier</p>
                    <p class="mt-1 font-semibold text-stone-800">{{ $purchaseOrder->supplier->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-stone-500">Tanggal Pesanan</p>
                    <p class="mt-1 font-semibold text-stone-800">
                        {{ $purchaseOrder->tanggal_pesanan?->format('d F Y') ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-stone-500">Total Pembelian</p>
                    <p class="mt-1 font-semibold text-stone-800">Rp {{ number_format($purchaseOrder->total, 0, ',', '.') }}</p>
                </div>
            </div>

            @if ($receipt?->foto_bukti)
                <div class="mt-5 border-t border-stone-100 pt-5">
                    <p class="mb-2 text-sm font-semibold text-stone-700">Foto Bukti Penerimaan</p>
                    <a href="{{ route('gudang.penerimaan.foto', $receipt) }}" target="_blank" rel="noopener">
                        <img
                            src="{{ route('gudang.penerimaan.foto', $receipt) }}"
                            alt="Bukti penerimaan PO-{{ str_pad($purchaseOrder->id, 5, '0', STR_PAD_LEFT) }}"
                            class="max-h-64 rounded-xl border border-stone-200 object-contain"
                        >
                    </a>
                </div>
            @endif
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5">
                <h2 class="text-xl font-bold text-stone-900">
                    {{ $isWaiting ? 'Pemeriksaan Produk' : ($needsFollowUp ? 'Tindak Lanjut Pemeriksaan' : 'Detail Penerimaan') }}
                </h2>
                <p class="mt-1 text-sm text-stone-500">
                    Periksa jumlah pesanan, jumlah diterima, barang baik, dan kondisi setiap produk.
                </p>
            </div>

            @if ($isWaiting)
                <form action="{{ route('gudang.penerimaan.verifikasi', $purchaseOrder) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-5">
                        @foreach ($purchaseOrder->items as $item)
                            <article class="rounded-2xl border border-stone-200 bg-stone-50 p-5">
                                <div class="mb-4">
                                    <p class="text-xs font-medium uppercase tracking-wider text-stone-500">SKU: {{ $item->product->sku ?? '-' }}</p>
                                    <h3 class="mt-1 text-lg font-bold text-stone-900">{{ $item->product->nama ?? 'Produk tidak ditemukan' }}</h3>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-3">
                                    <div>
                                        <label class="mb-2 block text-sm font-semibold text-stone-700">Jumlah Pesanan</label>
                                        <div class="rounded-xl border border-stone-200 bg-white px-4 py-3 font-bold text-stone-800">{{ $item->jumlah }} unit</div>
                                    </div>
                                    <div>
                                        <label for="diterima_{{ $item->produk_id }}" class="mb-2 block text-sm font-semibold text-stone-700">Jumlah Diterima</label>
                                        <input
                                            type="number"
                                            id="diterima_{{ $item->produk_id }}"
                                            name="items[{{ $item->produk_id }}][jumlah_diterima]"
                                            min="0"
                                            max="1000000"
                                            value="{{ old('items.'.$item->produk_id.'.jumlah_diterima', $item->jumlah) }}"
                                            required
                                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-800 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                        >
                                    </div>
                                    <div>
                                        <label for="rusak_{{ $item->produk_id }}" class="mb-2 block text-sm font-semibold text-stone-700">Jumlah Rusak</label>
                                        <input
                                            type="number"
                                            id="rusak_{{ $item->produk_id }}"
                                            name="items[{{ $item->produk_id }}][jumlah_rusak]"
                                            min="0"
                                            max="1000000"
                                            value="{{ old('items.'.$item->produk_id.'.jumlah_rusak', 0) }}"
                                            required
                                            class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-800 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                        >
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="catatan_{{ $item->produk_id }}" class="mb-2 block text-sm font-semibold text-stone-700">Catatan Kondisi Barang</label>
                                    <textarea
                                        id="catatan_{{ $item->produk_id }}"
                                        name="items[{{ $item->produk_id }}][catatan]"
                                        rows="2"
                                        placeholder="Catat kondisi, selisih, atau kerusakan barang..."
                                        class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-800 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100"
                                    >{{ old('items.'.$item->produk_id.'.catatan') }}</textarea>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-6 rounded-2xl border border-stone-200 p-5">
                        <h3 class="text-lg font-bold text-stone-900">Hasil Pemeriksaan</h3>
                        <div class="mt-4 grid gap-3 md:grid-cols-3">
                            @foreach ([
                                'disetujui' => ['Disetujui', 'Barang sesuai pesanan dan tidak rusak.', 'emerald'],
                                'perlu_pemeriksaan' => ['Perlu Pemeriksaan', 'Ada selisih atau kerusakan yang perlu ditindaklanjuti.', 'orange'],
                                'ditolak' => ['Ditolak', 'Tolak seluruh penerimaan dan jangan tambahkan stok.', 'red'],
                            ] as $value => [$label, $description, $color])
                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-{{ $color }}-200 bg-{{ $color }}-50 p-4">
                                    <input type="radio" name="keputusan" value="{{ $value }}" {{ old('keputusan') === $value ? 'checked' : '' }} required class="mt-1">
                                    <span>
                                        <strong class="block text-{{ $color }}-800">{{ $label }}</strong>
                                        <span class="mt-1 block text-sm text-{{ $color }}-700">{{ $description }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-5">
                            <label for="foto_bukti" class="mb-2 block text-sm font-semibold text-stone-700">Foto Bukti Penerimaan</label>
                            <input id="foto_bukti" name="foto_bukti" type="file" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm">
                            <p class="mt-1 text-xs text-stone-500">JPG, PNG, atau WEBP; maksimal 4 MB.</p>
                            @error('foto_bukti') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-5">
                            <label for="catatan" class="mb-2 block text-sm font-semibold text-stone-700">Catatan Pemeriksaan Keseluruhan</label>
                            <textarea id="catatan" name="catatan" rows="3" placeholder="Wajib diisi jika ada selisih atau barang rusak." class="w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-sm text-stone-800">{{ old('catatan') }}</textarea>
                            @error('catatan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                        <a href="{{ route('gudang.penerimaan') }}" class="inline-flex items-center justify-center rounded-xl border border-stone-200 px-5 py-3 text-sm font-semibold text-stone-700 hover:bg-stone-100">Kembali</a>
                        <button type="submit" class="rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-stone-900 hover:bg-amber-400">Simpan Hasil Pemeriksaan</button>
                    </div>
                </form>
            @elseif ($needsFollowUp)
                <div class="mb-5 rounded-2xl border border-orange-200 bg-orange-50 p-4 text-sm text-orange-900">
                    <p class="font-semibold">Penerimaan ini belum menambah stok.</p>
                    <p class="mt-1">{{ $receipt->catatan ?: 'Silakan tinjau jumlah barang baik sebelum menentukan tindak lanjut.' }}</p>
                </div>

                <form action="{{ route('gudang.penerimaan.follow-up', $receipt) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-left text-sm">
                            <thead class="bg-stone-50 text-xs uppercase text-stone-500">
                                <tr>
                                    <th class="px-4 py-3">Produk</th>
                                    <th class="px-4 py-3">Dipesan</th>
                                    <th class="px-4 py-3">Diterima</th>
                                    <th class="px-4 py-3">Rusak</th>
                                    <th class="px-4 py-3">Hilang</th>
                                    <th class="px-4 py-3">Barang Baik Disetujui</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                @foreach ($purchaseOrder->items as $item)
                                    @php $detail = $receipt->details->firstWhere('produk_id', $item->produk_id); @endphp
                                    <tr>
                                        <td class="px-4 py-3 font-semibold">{{ $item->product->nama ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $item->jumlah }}</td>
                                        <td class="px-4 py-3">{{ $detail?->jumlah_diterima ?? 0 }}</td>
                                        <td class="px-4 py-3">{{ $detail?->jumlah_rusak ?? 0 }}</td>
                                        <td class="px-4 py-3">{{ $detail?->jumlah_hilang ?? 0 }}</td>
                                        <td class="px-4 py-3">
                                            <input
                                                type="number"
                                                name="items[{{ $item->produk_id }}][jumlah]"
                                                min="0"
                                                max="{{ max((int) ($detail?->jumlah_diterima ?? 0) - (int) ($detail?->jumlah_rusak ?? 0), 0) }}"
                                                value="{{ old('items.'.$item->produk_id.'.jumlah', $detail?->jumlah ?? 0) }}"
                                                required
                                                class="w-32 rounded-lg border border-stone-200 px-3 py-2"
                                            >
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5">
                        <label for="tindak_lanjut" class="mb-2 block text-sm font-semibold text-stone-700">Catatan Tindak Lanjut</label>
                        <textarea id="tindak_lanjut" name="catatan" rows="3" required class="w-full rounded-xl border border-stone-200 px-4 py-3">{{ old('catatan', $receipt->tindak_lanjut) }}</textarea>
                        @error('catatan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-5 flex flex-wrap justify-end gap-3">
                        <button name="keputusan" value="ditolak" class="rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm font-bold text-red-700">Tolak Penerimaan</button>
                        <button name="keputusan" value="disetujui" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white">Setujui Barang Baik</button>
                    </div>
                </form>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] text-left text-sm">
                        <thead class="bg-stone-50 text-xs uppercase text-stone-500">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Dipesan</th>
                                <th class="px-4 py-3">Diterima</th>
                                <th class="px-4 py-3">Barang Baik</th>
                                <th class="px-4 py-3">Rusak</th>
                                <th class="px-4 py-3">Hilang</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach ($purchaseOrder->items as $item)
                                @php $detail = $receipt?->details->firstWhere('produk_id', $item->produk_id); @endphp
                                <tr>
                                    <td class="px-4 py-3 font-semibold">{{ $item->product->nama ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $item->jumlah }}</td>
                                    <td class="px-4 py-3">{{ $detail?->jumlah_diterima ?? 0 }}</td>
                                    <td class="px-4 py-3">{{ $detail?->jumlah ?? 0 }}</td>
                                    <td class="px-4 py-3">{{ $detail?->jumlah_rusak ?? 0 }}</td>
                                    <td class="px-4 py-3">{{ $detail?->jumlah_hilang ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <p class="text-sm text-stone-600"><strong>Petugas:</strong> {{ $receipt?->user?->name ?? '-' }}</p>
                    <p class="text-sm text-stone-600"><strong>Ditindaklanjuti oleh:</strong> {{ $receipt?->reviewedBy?->name ?? '-' }}</p>
                    <p class="text-sm text-stone-600"><strong>Catatan pemeriksaan:</strong> {{ $receipt?->catatan ?? '-' }}</p>
                    <p class="text-sm text-stone-600"><strong>Tindak lanjut:</strong> {{ $receipt?->tindak_lanjut ?? '-' }}</p>
                </div>
            @endif

            @if ($errors->has('keputusan') || $errors->has('items'))
                <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ $errors->first('keputusan') ?: $errors->first('items') }}
                </div>
            @endif
        </div>

        <a href="{{ route('gudang.penerimaan') }}" class="inline-flex rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold text-stone-700 hover:bg-stone-50">Kembali ke Daftar Penerimaan</a>
    </section>
@endsection
