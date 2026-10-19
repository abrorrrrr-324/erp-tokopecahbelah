@extends('layouts.dashboard')

@section('title', 'Edit Produk')
@section('page_kicker', 'Admin')
@section('page_title', 'Edit Produk')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-stone-500">Ubah data katalog produk</p>
                    <h2 class="mt-1 text-2xl font-bold text-stone-900">Edit Produk</h2>
                </div>


            </div>

            @include('admin.katalog._form', ['product' => $product, 'isEditing' => true])
        </div>
    </div>
@endsection
