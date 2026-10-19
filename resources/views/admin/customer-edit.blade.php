@extends('layouts.dashboard')

@section('title', 'Edit Customer')
@section('page_kicker', 'Admin')
@section('page_title', 'Edit Customer')

@section('content')
    <div class="mx-auto max-w-2xl rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-stone-500">Ubah data customer</p>
                <h2 class="text-2xl font-bold text-stone-900">{{ $customer->name }}</h2>
            </div>
            <a href="{{ route('admin.customer.show', $customer) }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:bg-stone-50">
                Detail
            </a>
        </div>

        <form action="{{ route('admin.customer.update', $customer) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-1 block text-sm font-medium text-stone-700">Nama lengkap</label>
                <input type="text" name="name" value="{{ old('name', $customer->name) }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-stone-700">Email</label>
                <input type="email" name="email" value="{{ old('email', $customer->email) }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none" required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-stone-700">Password Baru</label>
                <input type="password" name="password" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-stone-700">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-amber-500 focus:outline-none">
            </div>

            <button type="submit" class="w-full rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
