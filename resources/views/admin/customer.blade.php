@extends('layouts.dashboard')

@section('title', 'Customer')
@section('page_kicker', 'Admin')
@section('page_title', 'Data Customer')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Total Customer</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Customer Aktif</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['active'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Customer Baru</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['newThisMonth'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Update Terakhir</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['lastUpdated'] }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-xl font-bold text-stone-900">Daftar Customer</h3>
                <a href="{{ route('admin.customer.create') }}" class="rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400">
                    + Tambah Customer
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Nama</th>
                            <th class="pb-3 font-semibold">Email</th>
                            <th class="pb-3 font-semibold">Dibuat</th>
                            <th class="pb-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-stone-900">{{ $customer->name }}</td>
                                <td class="py-3 pr-4">{{ $customer->email }}</td>
                                <td class="py-3 pr-4">{{ $customer->created_at?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="py-3 pr-4">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.customer.show', $customer) }}" class="rounded-lg bg-stone-100 px-2.5 py-1.5 text-xs font-semibold text-stone-700 hover:bg-stone-200">Detail</a>
                                        <a href="{{ route('admin.customer.edit', $customer) }}" class="rounded-lg bg-amber-100 px-2.5 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-200">Edit</a>
                                        <form action="{{ route('admin.customer.destroy', $customer) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus customer {{ addslashes($customer->name) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg bg-red-100 px-2.5 py-1.5 text-xs font-semibold text-red-700 hover:bg-red-200">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-sm text-stone-500">Belum ada customer yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
