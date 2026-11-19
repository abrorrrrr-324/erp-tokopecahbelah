@extends('layouts.dashboard')

@section('title', 'Buat Purchase Order')
@section('page_kicker', 'Admin')
@section('page_title', 'Buat Pesanan Pembelian')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-3 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-stone-500">
                Pilih supplier dan centang produk yang ingin dipesan.
            </p>
            <h2 class="mt-1 text-xl font-bold text-stone-900">
                Pesanan Pembelian Baru
            </h2>
        </div>
    </div>

    {{-- Error --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
            <p class="font-semibold">Periksa kembali pesanan:</p>
            <ul class="mt-2 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Supplier kosong --}}
    @if ($suppliers->isEmpty())
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
            Belum ada supplier aktif. Tambahkan supplier terlebih dahulu melalui menu Master Supplier.
        </div>
    @endif

    <form action="{{ route('admin.pembelian.store') }}" method="POST" id="purchase-order-form" class="space-y-6">
        @csrf

        {{-- Data Supplier --}}
        <section class="space-y-5 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">

            <div class="space-y-2">
                <label for="supplier_id" class="text-sm font-semibold text-stone-700">
                    Pilih Supplier
                </label>

                <select
                    id="supplier_id"
                    name="supplier_id"
                    required
                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-3 text-stone-900 focus:border-amber-400 focus:outline-none">

                    <option value="">-- Pilih Supplier --</option>

                    @foreach ($suppliers as $supplier)
                        <option
                            value="{{ $supplier->id }}"
                            @selected((string) old('supplier_id') === (string) $supplier->id)>
                            {{ $supplier->nama }}
                            {{ $supplier->user ? ' - '.$supplier->user->email : '' }}
                        </option>
                    @endforeach

                </select>

                @error('supplier_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Daftar Produk --}}
            <div class="space-y-4">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-semibold text-stone-900">
                            Daftar Produk Supplier
                        </h3>
                        <p class="text-sm text-stone-500">
                            Centang produk dan masukkan jumlah yang ingin dipesan.
                        </p>
                    </div>

                    <div class="rounded-xl bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700">
                        <span id="selected-count">0</span> Produk Dipilih
                    </div>
                </div>

                {{-- Tabel Produk --}}
                <div class="overflow-x-auto rounded-2xl border border-stone-200">

                    <table class="w-full min-w-[750px] text-left text-sm">

                        <thead class="bg-stone-100 text-xs uppercase tracking-wide text-stone-600">
                            <tr>
                                <th class="w-16 px-4 py-4 text-center">
                                    Pilih
                                </th>
                                <th class="px-4 py-4">
                                    Nama Produk
                                </th>
                                <th class="px-4 py-4">
                                    SKU
                                </th>
                                <th class="px-4 py-4">
                                    Harga Beli
                                </th>
                                <th class="px-4 py-4">
                                    Satuan
                                </th>
                                <th class="w-40 px-4 py-4">
                                    Jumlah Pesanan
                                </th>
                            </tr>
                        </thead>

                        <tbody id="product-list" class="divide-y divide-stone-100">

                            @forelse ($products as $product)

                                @php
                                    $oldItems = old('items', []);

                                    $oldItem = collect($oldItems)->first(function ($item) use ($product) {
                                        return (string) ($item['product_id'] ?? '') === (string) $product->id;
                                    });

                                    $isSelected = $oldItem !== null;
                                @endphp

                                <tr
                                    class="product-row transition hover:bg-stone-50"
                                    data-supplier-id="{{ $product->supplier_id }}">

                                    {{-- Checkbox --}}
                                    <td class="px-4 py-4 text-center">
                                        <input
                                            type="checkbox"
                                            class="product-checkbox h-5 w-5 cursor-pointer rounded border-stone-300 text-amber-500 focus:ring-amber-400"
                                            data-product-id="{{ $product->id }}"
                                            {{ $isSelected ? 'checked' : '' }}>
                                    </td>

                                    {{-- Nama Produk --}}
                                    <td class="px-4 py-4">
                                        <span class="font-semibold text-stone-800">
                                            {{ $product->nama }}
                                        </span>
                                    </td>

                                    {{-- SKU --}}
                                    <td class="px-4 py-4 text-stone-500">
                                        {{ $product->sku }}
                                    </td>

                                    {{-- Harga --}}
                                    <td class="px-4 py-4 font-medium text-stone-800">
                                        Rp {{ number_format((float) $product->harga_beli, 0, ',', '.') }}
                                    </td>

                                    {{-- Satuan --}}
                                    <td class="px-4 py-4 text-stone-500">
                                        {{ $product->satuan }}
                                    </td>

                                    {{-- Jumlah --}}
                                    <td class="px-4 py-4">

                                        <input
                                            type="hidden"
                                            name="items[{{ $loop->index }}][product_id]"
                                            value="{{ $product->id }}"
                                            class="product-id-input"
                                            {{ $isSelected ? '' : 'disabled' }}>

                                        <input
                                            type="number"
                                            name="items[{{ $loop->index }}][jumlah]"
                                            min="1"
                                            max="1000000"
                                            value="{{ $oldItem['jumlah'] ?? 1 }}"
                                            class="quantity-input w-28 rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-stone-800 focus:border-amber-400 focus:outline-none"
                                            {{ $isSelected ? '' : 'disabled' }}>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-stone-500">
                                        Belum ada produk yang tersedia.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>

                </div>

                <p class="text-xs text-stone-500">
                    * Produk hanya akan dimasukkan ke Purchase Order jika dicentang.
                </p>

                @error('items')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>

            {{-- Catatan --}}
            <div class="space-y-2">
                <label for="catatan" class="text-sm font-semibold text-stone-700">
                    Catatan untuk Supplier (Opsional)
                </label>

                <textarea
                    id="catatan"
                    name="catatan"
                    rows="3"
                    maxlength="2000"
                    placeholder="Tuliskan catatan atau instruksi untuk supplier..."
                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">{{ old('catatan') }}</textarea>

                @error('catatan')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </section>

        {{-- Tombol --}}
        <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">

            <a href="{{ route('admin.pembelian') }}"
               class="rounded-xl border border-stone-200 px-5 py-3 text-center text-sm font-semibold text-stone-700 hover:bg-stone-50">
                Batal
            </a>

            <button
                type="submit"
                id="submit-button"
                @disabled($suppliers->isEmpty() || $products->isEmpty())
                class="rounded-xl bg-amber-500 px-6 py-3 text-sm font-bold text-stone-900 shadow-sm transition hover:bg-amber-400 disabled:cursor-not-allowed disabled:opacity-50">
                Lanjut
            </button>

        </div>

    </form>

</div>

{{-- JavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const supplierSelect = document.getElementById('supplier_id');
    const productRows = document.querySelectorAll('.product-row');
    const selectedCount = document.getElementById('selected-count');
    const form = document.getElementById('purchase-order-form');

    function updateSelectedCount() {
        const total = document.querySelectorAll(
            '.product-checkbox:checked'
        ).length;

        selectedCount.textContent = total;
    }

    function updateProducts(resetSelection = false) {

        const supplierId = supplierSelect.value;

        productRows.forEach(function (row) {

            const checkbox = row.querySelector('.product-checkbox');
            const productId = row.querySelector('.product-id-input');
            const quantity = row.querySelector('.quantity-input');

            const belongsToSupplier =
                supplierId !== '' &&
                row.dataset.supplierId === supplierId;

            row.hidden = !belongsToSupplier;

            if (!belongsToSupplier || resetSelection) {
                checkbox.checked = false;
            }

            checkbox.disabled = !belongsToSupplier;

            productId.disabled = !belongsToSupplier || !checkbox.checked;
            quantity.disabled = !belongsToSupplier || !checkbox.checked;

        });

        updateSelectedCount();
    }

    // Ketika checkbox berubah
    productRows.forEach(function (row) {

        const checkbox = row.querySelector('.product-checkbox');
        const productId = row.querySelector('.product-id-input');
        const quantity = row.querySelector('.quantity-input');

        checkbox.addEventListener('change', function () {

            productId.disabled = !checkbox.checked;
            quantity.disabled = !checkbox.checked;

            updateSelectedCount();

        });

    });

    // Ketika supplier diganti
    supplierSelect.addEventListener('change', function () {
        updateProducts(true);
    });

    // Validasi sebelum submit
    form.addEventListener('submit', function (event) {

        const totalSelected = document.querySelectorAll(
            '.product-checkbox:checked'
        ).length;

        if (!supplierSelect.value) {
            event.preventDefault();
            alert('Silakan pilih supplier terlebih dahulu.');
            supplierSelect.focus();
            return;
        }

        if (totalSelected === 0) {
            event.preventDefault();
            alert('Silakan centang minimal satu produk yang ingin dipesan.');
            return;
        }

        let invalidQuantity = false;

        productRows.forEach(function (row) {

            const checkbox = row.querySelector('.product-checkbox');

            if (!checkbox.checked) {
                return;
            }

            const quantity = row.querySelector('.quantity-input');

            if (
                quantity.value === '' ||
                !Number.isInteger(Number(quantity.value)) ||
                Number(quantity.value) < 1 ||
                Number(quantity.value) > 1000000
            ) {
                invalidQuantity = true;
            }

        });

        if (invalidQuantity) {
            event.preventDefault();
            alert('Jumlah pesanan harus berupa angka minimal 1.');
        }

    });

    // Tampilan awal
    updateProducts(false);

});
</script>
@endsection