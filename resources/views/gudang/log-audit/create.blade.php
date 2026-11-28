@extends('layouts.dashboard')

@section('title', 'Tambah Log Kerusakan')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Tambah Log Kerusakan')

@section('content')
    <section class="mt-8 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">

        {{-- HEADER --}}
        <div class="mb-6">
            <h2 class="text-xl font-bold text-stone-900">
                Formulir Pencatatan Kerusakan
            </h2>
            <p class="mt-1 text-sm text-stone-500">
                Lengkapi informasi barang rusak dan lampirkan foto sebagai bukti.
            </p>
        </div>

        {{-- FORM --}}
        <form action="{{ route('gudang.log-audit.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- PILIH PRODUK --}}
                <div class="md:col-span-2">
                    <label for="produk_id" class="mb-2 block text-sm font-semibold text-stone-700">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>

                    <select name="produk_id" id="produk_id"
                        class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                        <option value="">-- Pilih Produk --</option>

                        @foreach ($produks as $produk)
                            <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                                {{ $produk->nama }} (Stok: {{ $produk->stok }})
                            </option>
                        @endforeach

                    </select>

                    @error('produk_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- JUMLAH RUSAK --}}
                <div>
                    <label for="jumlah_rusak" class="mb-2 block text-sm font-semibold text-stone-700">
                        Jumlah Barang Rusak <span class="text-red-500">*</span>
                    </label>

                    <input type="number" name="jumlah_rusak" id="jumlah_rusak" min="1"
                        value="{{ old('jumlah_rusak') }}" placeholder="Masukkan jumlah barang rusak"
                        class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                    @error('jumlah_rusak')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- TANGGAL KERUSAKAN --}}
                <div>
                    <label for="tanggal_rusak" class="mb-2 block text-sm font-semibold text-stone-700">
                        Tanggal Kerusakan <span class="text-red-500">*</span>
                    </label>

                    <input type="date" name="tanggal_rusak" id="tanggal_rusak" value="{{ old('tanggal_rusak') }}"
                        class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">

                    @error('tanggal_rusak')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- KETERANGAN --}}
                <div class="md:col-span-2">
                    <label for="keterangan_kerusakan" class="mb-2 block text-sm font-semibold text-stone-700">
                        Keterangan Kerusakan <span class="text-red-500">*</span>
                    </label>

                    <textarea name="keterangan_kerusakan" id="keterangan_kerusakan" rows="4"
                        placeholder="Jelaskan kondisi dan penyebab kerusakan barang..."
                        class="w-full rounded-xl border border-stone-300 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-amber-500 focus:ring-2 focus:ring-amber-100">{{ old('keterangan_kerusakan') }}</textarea>

                    @error('keterangan_kerusakan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- FOTO BUKTI --}}
                <div class="md:col-span-2">
                    <label for="foto_bukti" class="mb-2 block text-sm font-semibold text-stone-700">
                        Foto Bukti Kerusakan
                    </label>

                    <input type="file" name="foto_bukti" id="foto_bukti" accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-700 file:mr-4 file:rounded-lg file:border-0 file:bg-amber-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-amber-800">

                    <p class="mt-2 text-xs text-stone-500">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>

                    @error('foto_bukti')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- TOMBOL --}}
            <div class="mt-8 flex flex-col-reverse gap-3 border-t border-stone-100 pt-6 sm:flex-row sm:justify-end">

                <a href="{{ route('gudang.log-audit.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-stone-300 px-5 py-3 text-sm font-semibold text-stone-700 transition hover:bg-stone-50">
                    Batal
                </a>

                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-5 py-3 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                    Simpan Log Kerusakan
                </button>

            </div>

        </form>
    </section>
@endsection
