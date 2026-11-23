@extends('layouts.dashboard')

@section('title', 'Catat Audit Barang')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Catat Barang Rusak / Hilang')

@section('content')
    <section class="mt-8 max-w-3xl rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <p class="mb-6 text-sm text-stone-500">Catatan akan menunggu pemeriksaan. Stok baru berkurang setelah audit disetujui.</p>
        <form action="{{ route('gudang.audit.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label for="produk_id" class="mb-2 block text-sm font-semibold text-stone-700">Produk</label>
                <select id="produk_id" name="produk_id" required class="w-full rounded-xl border border-stone-200 px-4 py-3 text-sm">
                    <option value="">Pilih produk</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" @selected(old('produk_id') == $product->id)>{{ $product->nama }} (stok {{ $product->stok }} {{ $product->satuan }})</option>
                    @endforeach
                </select>
                @error('produk_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="jenis" class="mb-2 block text-sm font-semibold text-stone-700">Jenis Audit</label>
                    <select id="jenis" name="jenis" required class="w-full rounded-xl border border-stone-200 px-4 py-3 text-sm">
                        <option value="">Pilih jenis</option>
                        <option value="rusak" @selected(old('jenis') === 'rusak')>Rusak</option>
                        <option value="hilang" @selected(old('jenis') === 'hilang')>Hilang</option>
                    </select>
                    @error('jenis') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="jumlah" class="mb-2 block text-sm font-semibold text-stone-700">Jumlah</label>
                    <input id="jumlah" name="jumlah" type="number" min="1" max="1000000" value="{{ old('jumlah') }}" required class="w-full rounded-xl border border-stone-200 px-4 py-3 text-sm">
                    @error('jumlah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label for="penyebab" class="mb-2 block text-sm font-semibold text-stone-700">Jenis / Penyebab Kerusakan atau Kehilangan</label>
                <input id="penyebab" name="penyebab" value="{{ old('penyebab') }}" required maxlength="255" class="w-full rounded-xl border border-stone-200 px-4 py-3 text-sm">
                @error('penyebab') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="foto_bukti" class="mb-2 block text-sm font-semibold text-stone-700">Foto Bukti</label>
                <input id="foto_bukti" name="foto_bukti" type="file" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-stone-200 px-4 py-3 text-sm">
                <p class="mt-1 text-xs text-stone-500">JPG, PNG, atau WEBP; maksimal 4 MB.</p>
                @error('foto_bukti') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="catatan" class="mb-2 block text-sm font-semibold text-stone-700">Keterangan</label>
                <textarea id="catatan" name="catatan" rows="3" class="w-full rounded-xl border border-stone-200 px-4 py-3 text-sm">{{ old('catatan') }}</textarea>
                @error('catatan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                <a href="{{ route('gudang.audit') }}" class="rounded-xl border border-stone-200 px-5 py-3 text-center text-sm font-semibold text-stone-700 hover:bg-stone-50">Kembali</a>
                <button class="rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-stone-900 hover:bg-amber-400">Simpan Audit</button>
            </div>
        </form>
    </section>
@endsection
