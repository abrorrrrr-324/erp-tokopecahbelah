@extends('layouts.dashboard')

@section('title', 'Tambah Produk Katalog')
@section('page_kicker', 'Supplier')
@section('page_title', 'Tambah Produk')

@section('content')
    <div class="mx-auto max-w-3xl space-y-5 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div>
            <p class="text-sm text-stone-500">Produk ini hanya tampil di katalog akun supplier Anda.</p>
        </div>
        @if ($categories->isEmpty())
            <p class="rounded-xl bg-amber-50 p-4 text-sm text-amber-800">Belum ada kategori produk. Hubungi admin untuk menambahkan kategori katalog.</p>
        @endif
        @include('supplier.katalog._form', ['product' => $product, 'categories' => $categories, 'isEditing' => false])
    </div>
@endsection
