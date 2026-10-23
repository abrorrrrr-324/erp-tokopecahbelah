@extends('layouts.dashboard')

@section('title', 'Master Katalog')
@section('page_kicker', 'Admin')
@section('page_title', 'Master Katalog')

@section('content')
    @php
        $statusClasses = [
            'Promo' => 'bg-emerald-100 text-emerald-700',
            'Hampir habis' => 'bg-amber-100 text-amber-700',
            'Aktif' => 'bg-stone-100 text-stone-700',
        ];
    @endphp

    <div class="space-y-6">

        {{-- Tabel Produk --}}
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">

            {{-- Judul dan Status --}}
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div>
                    <h2 class="mt-1 text-2xl font-bold text-stone-900">Daftar Produk</h2>
                    <p class="text-sm text-stone-500">Kelola katalog produk toko</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                        Aktif
                    </span>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Promo
                    </span>
                </div>
            </div>

            {{-- Form Pencarian --}}
            <form action="{{ route('admin.katalog') }}" method="GET"
                class="mb-6 flex flex-col gap-3 sm:flex-row">

                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-stone-400"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama produk, kategori, atau status..."
                        class="w-full rounded-xl border border-stone-200 bg-stone-50 py-3 pl-10 pr-4 text-sm text-stone-800 outline-none transition placeholder:text-stone-400 focus:border-amber-400 focus:bg-white focus:ring-2 focus:ring-amber-100">
                </div>

                <button type="submit"
                    class="rounded-xl bg-stone-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-stone-700">
                    Cari Produk
                </button>

                <a href="{{ route('admin.katalog.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                + Tambah Produk
            </a>

                @if (request('search'))
                    <a href="{{ route('admin.katalog') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-stone-200 px-5 py-3 text-sm font-semibold text-stone-600 transition hover:bg-stone-100">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Informasi Hasil Pencarian --}}
            @if (request('search'))
                <div class="mb-4 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    Hasil pencarian untuk
                    <span class="font-semibold">"{{ request('search') }}"</span>:
                    <span class="font-semibold">{{ $products->count() }} produk ditemukan.</span>
                </div>
            @endif

            {{-- Tabel --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 pr-4 font-semibold">Produk</th>
                            <th class="pb-3 pr-4 font-semibold">Kategori</th>
                            <th class="pb-3 pr-4 font-semibold">Stok</th>
                            <th class="pb-3 pr-4 font-semibold">Harga</th>
                            <th class="pb-3 pr-4 font-semibold">Status</th>
                            <th class="pb-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr class="align-middle border-b border-stone-100 last:border-0 hover:bg-stone-50/70">

                                {{-- Produk --}}
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-3">
                                        <img
                                            src="{{ $product->photo ? \Illuminate\Support\Facades\Storage::url($product->photo) : 'https://placehold.co/80x80/f5f5f4/1c1917?text=Produk' }}"
                                            alt="{{ $product->name }}"
                                            class="h-12 w-12 rounded-xl object-cover ring-1 ring-stone-200">

                                        <p class="font-semibold text-stone-900">
                                            {{ $product->name }}
                                        </p>
                                    </div>
                                </td>

                                {{-- Kategori --}}
                                <td class="py-3 pr-4">
                                    {{ $product->category }}
                                </td>

                                {{-- Stok --}}
                                <td class="py-3 pr-4">
                                    {{ $product->stock }}
                                </td>

                                {{-- Harga --}}
                                <td class="py-3 pr-4 whitespace-nowrap">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>

                                {{-- Status --}}
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$product->status] ?? 'bg-stone-100 text-stone-700' }}">
                                        {{ $product->status }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.katalog.show', $product) }}"
                                            class="font-semibold text-stone-600 transition hover:text-stone-900">
                                            Detail
                                        </a>

                                        <a href="{{ route('admin.katalog.edit', $product) }}"
                                            class="font-semibold text-amber-600 transition hover:text-amber-500">
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('admin.katalog.destroy', $product) }}"
                                            method="POST"
                                            data-delete-form
                                            data-product-name="{{ $product->name }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" data-delete-trigger
                                                class="font-semibold text-red-600 transition hover:text-red-500">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="mb-3 h-10 w-10 text-stone-300"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
                                        </svg>

                                        <p class="font-semibold text-stone-700">
                                            {{ request('search') ? 'Produk tidak ditemukan' : 'Belum ada produk' }}
                                        </p>

                                        <p class="mt-1 text-sm text-stone-500">
                                            {{ request('search') ? 'Coba gunakan kata kunci pencarian yang lain.' : 'Produk yang ditambahkan akan muncul di sini.' }}
                                        </p>

                                        @if (request('search'))
                                            <a href="{{ route('admin.katalog') }}"
                                                class="mt-3 text-sm font-semibold text-amber-600 hover:text-amber-700">
                                                Tampilkan semua produk
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Jumlah Produk --}}
            <div class="mt-4 border-t border-stone-100 pt-4 text-sm text-stone-500">
                Menampilkan
                <span class="font-semibold text-stone-800">{{ $products->count() }}</span>
                produk
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-950/60 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="delete-modal-title">

        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
            <p id="delete-modal-title" class="text-lg font-semibold text-stone-900">
                {{ $products->first() ? 'Apakah anda yakin ingin menghapus produk ' . $products->first()->name . '?' : 'Apakah anda yakin ingin menghapus produk ini?' }}
            </p>

            <p class="mt-2 text-sm text-stone-500">
                Produk yang dihapus tidak akan ditampilkan lagi dalam katalog.
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="cancel-delete"
                    class="rounded-xl border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-100">
                    Tidak
                </button>

                <button type="button" id="confirm-delete"
                    class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- JavaScript Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('delete-modal');
            const modalTitle = document.getElementById('delete-modal-title');
            const cancelBtn = document.getElementById('cancel-delete');
            const confirmBtn = document.getElementById('confirm-delete');

            let selectedForm = null;

            document.querySelectorAll('[data-delete-trigger]').forEach(button => {
                button.addEventListener('click', function () {
                    selectedForm = this.closest('[data-delete-form]');

                    if (!selectedForm) return;

                    modalTitle.textContent =
                        'Apakah anda yakin ingin menghapus produk ' +
                        selectedForm.dataset.productName + '?';

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    cancelBtn.focus();
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                selectedForm = null;
            }

            cancelBtn.addEventListener('click', closeModal);

            confirmBtn.addEventListener('click', function () {
                if (selectedForm) {
                    selectedForm.submit();
                }
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
@endsection
