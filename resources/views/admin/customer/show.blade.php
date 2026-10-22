@extends('layouts.dashboard')

@section('title', 'Detail Customer')
@section('page_kicker', 'Admin')
@section('page_title', 'Detail Customer')

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-sm text-stone-500">Informasi customer</p>
                <h2 class="text-2xl font-bold text-stone-900">{{ $customer->name }}</h2>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.customer') }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-sm font-semibold text-stone-700 hover:bg-stone-50">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-2xl bg-stone-50 p-4">
                <p class="text-sm text-stone-500">Nama</p>
                <p class="mt-2 text-lg font-bold text-stone-900">{{ $customer->name }}</p>
            </div>
            <div class="rounded-2xl bg-stone-50 p-4">
                <p class="text-sm text-stone-500">Email</p>
                <p class="mt-2 text-lg font-bold text-stone-900">{{ $customer->email }}</p>
            </div>
            <div class="rounded-2xl bg-stone-50 p-4">
                <p class="text-sm text-stone-500">Role</p>
                <p class="mt-2 text-lg font-bold text-stone-900">{{ ucfirst($customer->role) }}</p>
            </div>
            <div class="rounded-2xl bg-stone-50 p-4">
                <p class="text-sm text-stone-500">Dibuat</p>
                <p class="mt-2 text-lg font-bold text-stone-900">{{ $customer->created_at?->translatedFormat('d M Y') ?? '-' }}</p>
            </div>
        </div>
    </div>
@endsection
