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
        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Kelola katalog produk toko</p>
                <h2 class="mt-1 text-2xl font-bold text-stone-900">Daftar Produk</h2>
            </div>
            <a href="{{ route('admin.katalog.create') }}" class="rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400">
                + Tambah Produk
            </a>
        </div>

        {{-- Tabel Produk --}}
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-xl font-bold text-stone-900">Produk Terbaru</h3>
                <div class="flex gap-2">
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Aktif</span>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Promo</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Produk</th>
                            <th class="pb-3 font-semibold">Kategori</th>
                            <th class="pb-3 font-semibold">Stok</th>
                            <th class="pb-3 font-semibold">Harga</th>
                            <th class="pb-3 font-semibold">Status</th>
                            <th class="pb-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="align-middle border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4">
                                    <div class="flex items-center gap-3">
                                        <img
                                            src="{{ $product->photo ? Storage::url($product->photo) : 'https://placehold.co/80x80/f5f5f4/1c1917?text=Produk' }}"
                                            alt="{{ $product->name }}"
                                            class="h-12 w-12 rounded-xl object-cover ring-1 ring-stone-200"
                                        >
                                        <p class="font-semibold text-stone-900">{{ $product->name }}</p>
                                    </div>
                                </td>
                                <td class="py-3 pr-4">{{ $product->category }}</td>
                                <td class="py-3 pr-4">{{ $product->stock }}</td>
                                <td class="py-3 pr-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$product->status] ?? 'bg-stone-100 text-stone-700' }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.katalog.show', $product) }}" class="font-semibold text-stone-600 hover:text-stone-500">Detail</a>
                                        <a href="{{ route('admin.katalog.edit', $product) }}" class="font-semibold text-amber-600 hover:text-amber-500">Edit</a>
                                        <form action="{{ route('admin.katalog.destroy', $product) }}" method="POST" data-delete-form data-product-name="{{ $product->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-delete-trigger class="font-semibold text-red-600 hover:text-red-500">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-stone-500">Belum ada produk yang tersimpan di database.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
            <p id="delete-modal-title" class="text-lg font-semibold text-stone-900">
                Apakah anda yakin ingin menghapus produk {{ $products->first()->name ?? '' }}?
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="cancel-delete" class="rounded-xl border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-100">Tidak</button>
                <button type="button" id="confirm-delete" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">Iya</button>
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

                    modalTitle.textContent = 'Apakah anda yakin ingin menghapus produk ' + selectedForm.dataset.productName + '?';
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
                if (selectedForm) selectedForm.submit();
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) closeModal();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        });
    </script>
@endsection
