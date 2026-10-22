@extends('layouts.dashboard')

@section('title', 'Tambah Customer')
@section('page_kicker', 'Admin')
@section('page_title', 'Tambah Customer')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-stone-500">Form pendaftaran customer</p>
                <h2 class="text-2xl font-bold text-stone-900">Data Baru</h2>
            </div>
            <a href="{{ route('admin.customer') }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:bg-stone-50">
                Kembali
            </a>
        </div>

        <form action="{{ route('admin.customer.store') }}" method="POST" class="space-y-5">
            @csrf
            @if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold">Gagal menambahkan customer:</p>

        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <div>
                <label class="mb-1 block text-sm font-medium text-stone-700">Nama lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" placeholder="Masukkan nama customer" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-stone-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" placeholder="customer@email.com" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-stone-700">Password</label>
                <input type="password" name="password" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-stone-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" required>
            </div>

            <button type="submit" class="w-full rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400">
                Tambah Customer
            </button>
        </form>
    </div>
@endsection
