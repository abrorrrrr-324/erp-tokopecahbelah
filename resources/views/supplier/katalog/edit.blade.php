@extends('layouts.dashboard')

@section('title', 'Ubah Produk Katalog')
@section('page_kicker', 'Supplier')
@section('page_title', 'Ubah Produk')

@section('content')
    <div class="mx-auto max-w-3xl space-y-5 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div>
            <p class="text-sm text-stone-500">Perbarui harga, stok, atau ketersediaan produk Anda.</p>
        </div>
        @include('supplier.katalog._form', ['product' => $product, 'categories' => $categories, 'isEditing' => true])
    </div>
@endsection
