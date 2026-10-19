
@extends('layouts.dashboard')

@section('title', $isEditing ? 'Edit Produk' : 'Tambah Produk')
@section('page_kicker', 'Admin')
@section('page_title', $isEditing ? 'Edit Produk' : 'Tambah Produk')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-stone-500">
                        {{ $isEditing ? 'Ubah data katalog produk' : 'Tambahkan produk ke katalog' }}
                    </p>

                    <h2 class="mt-1 text-2xl font-bold text-stone-900">
                        {{ $isEditing ? 'Edit Produk' : 'Tambah Produk Baru' }}
                    </h2>
                </div>

                <a
                    href="{{ route('admin.katalog') }}"
                    class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:border-stone-300 hover:bg-stone-50"
                >
                    Kembali
                </a>
            </div>

            <form
                action="{{ $isEditing ? route('admin.katalog.update', $product) : route('admin.katalog.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-6 grid gap-5 md:grid-cols-2"
                @if ($isEditing)
                    onsubmit="return confirm('Apakah Anda yakin ingin mengubah data produk ini?');"
                @endif
            >
                @csrf

                @if ($isEditing)
                    @method('PUT')
                @endif

                {{-- Nama Produk --}}
                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium text-stone-700">
                        Nama Produk
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $product->name) }}"
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white"
                        placeholder="Contoh: Kursi Kayu Minimalis"
                        required
                    >

                    @error('name')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="space-y-2">
                    <label for="category" class="text-sm font-medium text-stone-700">
                        Kategori
                    </label>

                    <input
                        id="category"
                        name="category"
                        type="text"
                        value="{{ old('category', $product->category) }}"
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white"
                        placeholder="Contoh: Furniture"
                        required
                    >

                    @error('category')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Stok --}}
                <div class="space-y-2">
                    <label for="stock" class="text-sm font-medium text-stone-700">
                        Stok
                    </label>

                    <input
                        id="stock"
                        name="stock"
                        type="number"
                        min="0"
                        value="{{ old('stock', $product->stock ?? 0) }}"
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white"
                        required
                    >

                    @error('stock')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Harga --}}
                <div class="space-y-2">
                    <label for="price" class="text-sm font-medium text-stone-700">
                        Harga
                    </label>

                    <input
                        id="price"
                        name="price"
                        type="number"
                        min="0"
                        value="{{ old('price', $product->price ?? 0) }}"
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white"
                        required
                    >

                    @error('price')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="space-y-2 md:col-span-2">
                    <label for="status" class="text-sm font-medium text-stone-700">
                        Status
                    </label>

                    @php
                        $selectedStatus = old('status', $product->status ?? 'Aktif');
                    @endphp

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white"
                        required
                    >
                        <option value="Aktif" {{ $selectedStatus === 'Aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="Promo" {{ $selectedStatus === 'Promo' ? 'selected' : '' }}>
                            Promo
                        </option>

                        <option value="Hampir habis" {{ $selectedStatus === 'Hampir habis' ? 'selected' : '' }}>
                            Hampir habis
                        </option>
                    </select>

                    @error('status')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Foto Produk --}}
                <div class="space-y-2 md:col-span-2">
                    <label for="photo" class="text-sm font-medium text-stone-700">
                        Foto Produk
                    </label>

                    <input
                        id="photo"
                        name="photo"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        class="w-full rounded-xl border border-dashed border-stone-300 bg-stone-50 px-3 py-2.5 text-stone-700 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-amber-700"
                    >

                    @error('photo')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror

                    @if ($product->exists && $product->photo)
                        <div class="mt-3 flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 p-3">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($product->photo) }}"
                                alt="Foto {{ $product->name }}"
                                class="h-16 w-16 rounded-xl object-cover"
                            >

                            <span class="text-sm text-stone-600">
                                Foto saat ini
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-3 md:col-span-2">
                    <a
                        href="{{ route('admin.katalog') }}"
                        class="rounded-xl border border-stone-200 px-5 py-2.5 text-sm font-semibold text-stone-700 hover:border-stone-300 hover:bg-stone-50"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-stone-900 transition hover:bg-amber-400"
                    >
                        {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Produk' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
