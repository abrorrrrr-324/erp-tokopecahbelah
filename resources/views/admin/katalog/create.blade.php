@extends('layouts.dashboard')

@section('title', 'Tambah Produk')
@section('page_kicker', 'Admin')
@section('page_title', 'Tambah Produk')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-stone-500">Tambahkan produk ke katalog</p>
                    <h2 class="mt-1 text-2xl font-bold text-stone-900">Tambah Produk Baru</h2>
                </div>

                <a
                    href="{{ route('admin.katalog') }}"
                    class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:border-stone-300 hover:bg-stone-50"
                >
                    Kembali
                </a>
            </div>

            @include('admin.katalog._form', ['product' => $product, 'isEditing' => false])
        </div>
    </div>
@endsection
