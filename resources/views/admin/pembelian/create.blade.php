@extends('layouts.dashboard')

@section('title', 'Buat Purchase Order')
@section('page_kicker', 'Admin')
@section('page_title', 'Buat Pesanan Pembelian')

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-3 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">Produk dan harga diambil dari katalog supplier yang dipilih.</p>
                <h2 class="mt-1 text-xl font-bold text-stone-900">Pesanan baru</h2>
            </div>
            <a href="{{ route('admin.pembelian') }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-center text-sm font-semibold text-stone-700 hover:bg-stone-50">Kembali</a>
        </div>

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

        @if ($suppliers->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-800">
                Belum ada supplier aktif. Tambahkan supplier terlebih dahulu melalui menu Master Supplier.
            </div>
        @endif

        <form action="{{ route('admin.pembelian.store') }}" method="POST" class="space-y-6">
            @csrf
            <section class="space-y-5 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
                <div class="space-y-2">
                    <label for="supplier_id" class="text-sm font-semibold text-stone-700">Supplier</label>
                    <select id="supplier_id" name="supplier_id" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">
                        <option value="">Pilih supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected((string) old('supplier_id') === (string) $supplier->id)>
                                {{ $supplier->nama }}{{ $supplier->user ? ' - '.$supplier->user->email : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-stone-900">Produk pesanan</h3>
                            <p class="text-sm text-stone-500">SKU dan harga mengikuti katalog supplier.</p>
                        </div>
                        <button id="add-item" type="button" class="shrink-0 rounded-xl border border-stone-200 px-3 py-2 text-sm font-semibold text-stone-700 hover:bg-stone-50">+ Tambah Produk</button>
                    </div>

                    @php($formItems = old('items', [['product_id' => '', 'jumlah' => 1]]))
                    <div id="order-items" class="space-y-3">
                        @foreach ($formItems as $index => $item)
                            <div class="grid gap-3 rounded-2xl bg-stone-50 p-4 sm:grid-cols-[minmax(0,1fr)_140px_auto]">
                                <div class="space-y-1">
                                    <label for="product-{{ $index }}" class="text-xs font-semibold uppercase tracking-wide text-stone-500">Produk</label>
                                    <select id="product-{{ $index }}" name="items[{{ $index }}][product_id]" data-product-select required class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-amber-400 focus:outline-none">
                                        <option value="">Pilih produk</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-supplier-id="{{ $product->supplier_id }}" @selected((string) ($item['product_id'] ?? '') === (string) $product->id)>
                                                {{ $product->nama }} ({{ $product->sku }}) — Rp {{ number_format((float) $product->harga_beli, 0, ',', '.') }}/{{ $product->satuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error("items.$index.product_id") <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label for="quantity-{{ $index }}" class="text-xs font-semibold uppercase tracking-wide text-stone-500">Jumlah</label>
                                    <input id="quantity-{{ $index }}" name="items[{{ $index }}][jumlah]" type="number" min="1" max="1000000" value="{{ $item['jumlah'] ?? 1 }}" required class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-amber-400 focus:outline-none">
                                    @error("items.$index.jumlah") <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <button type="button" data-remove-item class="self-end rounded-xl px-3 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="catatan" class="text-sm font-semibold text-stone-700">Catatan untuk supplier (opsional)</label>
                    <textarea id="catatan" name="catatan" rows="3" maxlength="2000" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">{{ old('catatan') }}</textarea>
                    @error('catatan') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row">
                <a href="{{ route('admin.pembelian') }}" class="rounded-xl border border-stone-200 px-5 py-2.5 text-center text-sm font-semibold text-stone-700 hover:bg-stone-50">Batal</a>
                <button type="submit" @disabled($suppliers->isEmpty() || $products->isEmpty()) class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400 disabled:cursor-not-allowed disabled:opacity-50">Simpan Pesanan</button>
            </div>
        </form>

        <template id="item-row-template">
            <div class="grid gap-3 rounded-2xl bg-stone-50 p-4 sm:grid-cols-[minmax(0,1fr)_140px_auto]">
                <div class="space-y-1">
                    <label class="text-xs font-semibold uppercase tracking-wide text-stone-500">Produk</label>
                    <select name="items[__INDEX__][product_id]" data-product-select required class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-amber-400 focus:outline-none">
                        <option value="">Pilih produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-supplier-id="{{ $product->supplier_id }}">
                                {{ $product->nama }} ({{ $product->sku }}) — Rp {{ number_format((float) $product->harga_beli, 0, ',', '.') }}/{{ $product->satuan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-semibold uppercase tracking-wide text-stone-500">Jumlah</label>
                    <input name="items[__INDEX__][jumlah]" type="number" min="1" max="1000000" value="1" required class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm text-stone-900 focus:border-amber-400 focus:outline-none">
                </div>
                <button type="button" data-remove-item class="self-end rounded-xl px-3 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">Hapus</button>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const supplierSelect = document.getElementById('supplier_id');
            const itemsContainer = document.getElementById('order-items');
            const template = document.getElementById('item-row-template');
            const addItemButton = document.getElementById('add-item');
            let nextIndex = itemsContainer.children.length;

            function filterProducts(clearInvalidSelection) {
                const supplierId = supplierSelect.value;

                itemsContainer.querySelectorAll('[data-product-select]').forEach(function (select) {
                    Array.from(select.options).forEach(function (option) {
                        if (!option.value) {
                            return;
                        }

                        const belongsToSupplier = option.dataset.supplierId === supplierId;
                        option.hidden = !belongsToSupplier;
                        option.disabled = !belongsToSupplier;

                        if (clearInvalidSelection && !belongsToSupplier && option.selected) {
                            select.value = '';
                        }
                    });

                    select.disabled = !supplierId;
                });
            }

            supplierSelect.addEventListener('change', function () {
                filterProducts(true);
            });

            addItemButton.addEventListener('click', function () {
                const row = template.content.cloneNode(true);
                row.querySelectorAll('[name]').forEach(function (input) {
                    input.name = input.name.replace('__INDEX__', String(nextIndex));
                });
                itemsContainer.appendChild(row);
                nextIndex += 1;
                filterProducts(false);
            });

            itemsContainer.addEventListener('click', function (event) {
                const removeButton = event.target.closest('[data-remove-item]');

                if (!removeButton) {
                    return;
                }

                const rows = itemsContainer.children;
                if (rows.length === 1) {
                    rows[0].querySelector('[data-product-select]').value = '';
                    rows[0].querySelector('input[type="number"]').value = '1';
                    return;
                }

                removeButton.closest('.grid').remove();
            });

            filterProducts(false);
        });
    </script>
@endsection
