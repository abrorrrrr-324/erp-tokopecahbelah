@extends('layouts.dashboard')

@section('title', 'Katalog Barang')
@section('page_kicker', $role === 'kepala_gudang' ? 'Kepala Gudang' : 'Customer')
@section('page_title', 'Katalog Barang')

@section('content')
    @php
        $lowStock = $role === 'kepala_gudang'
            ? $products->filter(fn ($product) => $product->stok <= ($product->stok_minimum ?? 10))->count()
            : $products->where('stock', '<=', 10)->count();
        $totalValue = $products->sum('price');
    @endphp


    <section class="mt-8 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Daftar Produk</h2>
                <p class="text-sm text-stone-500">{{ $lowStock }} item stok rendah perlu perhatian</p>
            </div>
        </div>

        @php
            $catalogRoute = $role === 'kepala_gudang' ? route('gudang.katalog') : route('customer.katalog');
        @endphp

        <form action="{{ $catalogRoute }}" method="GET" class="mb-6 flex flex-col gap-3 sm:flex-row">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama produk, kategori..."
                    class="w-full rounded-xl border border-stone-200 bg-stone-50 py-3 pl-10 pr-4 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-amber-400 focus:bg-white focus:ring-2 focus:ring-amber-100">
            </div>

            @if ($role === 'kepala_gudang')
                <div>
                    <label for="status_stok" class="sr-only">Filter status stok</label>
                    <select
                        id="status_stok"
                        name="status_stok"
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-800 outline-none transition focus:border-amber-400 focus:bg-white focus:ring-2 focus:ring-amber-100 sm:min-w-48"
                    >
                        <option value="">Semua status stok</option>
                        <option value="habis" {{ request('status_stok') === 'habis' ? 'selected' : '' }}>Habis</option>
                        <option value="menipis" {{ request('status_stok') === 'menipis' ? 'selected' : '' }}>Menipis</option>
                        <option value="aman" {{ request('status_stok') === 'aman' ? 'selected' : '' }}>Aman</option>
                    </select>
                </div>
            @endif

            <button type="submit" class="rounded-xl bg-stone-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-stone-700">
                Cari
            </button>

            @if (request('search') || ($role === 'kepala_gudang' && request('status_stok')))
                <a href="{{ $catalogRoute }}" class="inline-flex items-center justify-center rounded-xl border border-stone-200 px-5 py-3 text-sm font-semibold text-stone-600 transition hover:bg-stone-100">
                    Reset
                </a>
            @endif
        </form>

        @if (request('search') || ($role === 'kepala_gudang' && request('status_stok')))
            <div class="mb-4 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800">
                @if (request('search'))
                    Hasil pencarian untuk <span class="font-semibold">"{{ request('search') }}"</span>.
                @endif
                @if ($role === 'kepala_gudang' && request('status_stok'))
                    Status stok: <span class="font-semibold">{{ ucfirst(request('status_stok')) }}</span>.
                @endif
                <span class="font-semibold">{{ $products->count() }} produk ditemukan.</span>
            </div>
        @endif

        @if ($products->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-4 py-8 text-center text-sm text-stone-500">
                Belum ada produk yang tersedia di katalog saat ini.
            </div>
        @elseif ($role === 'kepala_gudang')
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                            <th class="px-4 py-3 font-semibold">Produk</th>
                            <th class="px-4 py-3 font-semibold">Kategori</th>
                            <th class="px-4 py-3 font-semibold">Stok</th>
                            <th class="px-4 py-3 text-right font-semibold">Harga</th>
                            <th class="px-4 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($products as $product)
                            @php
                                $minimumStock = $product->stok_minimum ?? 10;
                                $stockStatus = $product->stok <= 0
                                    ? ['Habis', 'bg-red-100 text-red-700']
                                    : ($product->stok <= $minimumStock
                                        ? ['Menipis', 'bg-amber-100 text-amber-700']
                                        : ['Aman', 'bg-emerald-100 text-emerald-700']);
                            @endphp
                            <tr class="transition hover:bg-stone-50">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-stone-900">{{ $product->nama }}</p>
                                    <p class="mt-1 text-xs text-stone-500">{{ $product->sku ?: 'Tanpa SKU' }}</p>
                                </td>
                                <td class="px-4 py-4 text-stone-600">{{ $product->category?->nama ?? '-' }}</td>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span class="font-semibold text-stone-900">
                                        {{ number_format($product->stok, 0, ',', '.') }} {{ $product->satuan ?: 'unit' }}
                                    </span>
                                    <span class="ml-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $stockStatus[1] }}">
                                        {{ $stockStatus[0] }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right font-semibold text-stone-900">
                                    Rp {{ number_format((float) $product->harga_jual, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4 text-right">
                                    <a
                                        href="{{ route('gudang.katalog.show', $product) }}"
                                        class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-stone-900 transition hover:bg-amber-400"
                                    >
                                        Lihat detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($products as $product)
                    <article class="overflow-hidden rounded-2xl border border-stone-200 bg-stone-50 shadow-sm">
                        <img src="{{ $product->photo ? Storage::url($product->photo) : 'https://placehold.co/800x800/f5f5f4/1c1917?text=Produk' }}" alt="{{ $product->name }}" class="h-48 w-full object-cover">
                        <div class="space-y-4 p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-stone-500">{{ $product->category }}</p>
                                    <h3 class="mt-2 text-lg font-bold text-stone-900">{{ $product->name }}</h3>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-sm text-stone-600">
                                <span>Stok</span>
                                <strong class="font-bold text-stone-900">{{ $product->stock }} unit</strong>
                            </div>
                            <div class="flex items-center justify-between text-sm text-stone-600">
                                <span>Harga</span>
                                <strong class="font-bold text-stone-900">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                            </div>

                            <a href="{{ $role === 'kepala_gudang' ? route('gudang.katalog.show', $product) : route('customer.katalog.show', $product) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-amber-500 px-3 py-2.5 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                                Lihat detail
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
