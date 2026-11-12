<form action="{{ $isEditing ? route('supplier.katalog.update', $product->id) : route('supplier.katalog.store') }}" method="POST" class="grid gap-5 md:grid-cols-2">
    @csrf
    @if ($isEditing)
        @method('PUT')
    @endif

    <div class="space-y-2">
        <label for="sku" class="text-sm font-semibold text-stone-700">SKU</label>
        <input id="sku" name="sku" value="{{ old('sku', $product->sku) }}" maxlength="50" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">
        @error('sku') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="space-y-2">
        <label for="nama" class="text-sm font-semibold text-stone-700">Nama Produk</label>
        <input id="nama" name="nama" value="{{ old('nama', $product->nama) }}" maxlength="150" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">
        @error('nama') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="space-y-2">
        <label for="category_id" class="text-sm font-semibold text-stone-700">Kategori</label>
        <select id="category_id" name="category_id" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>{{ $category->nama }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="space-y-2">
        <label for="harga_beli" class="text-sm font-semibold text-stone-700">Harga Supplier</label>
        <input id="harga_beli" name="harga_beli" type="number" min="0" max="9999999999999.99" step="0.01" value="{{ old('harga_beli', $product->harga_beli ?? 0) }}" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">
        @error('harga_beli') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="space-y-2">
        <label for="satuan" class="text-sm font-semibold text-stone-700">Satuan</label>
        <input id="satuan" name="satuan" value="{{ old('satuan', $product->satuan ?? 'pcs') }}" maxlength="30" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">
        @error('satuan') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="space-y-2">
        <label for="stok" class="text-sm font-semibold text-stone-700">Stok Tersedia</label>
        <input id="stok" name="stok" type="number" min="0" value="{{ old('stok', $product->stok ?? 0) }}" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">
        @error('stok') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="space-y-2">
        <label for="status" class="text-sm font-semibold text-stone-700">Status Katalog</label>
        <select id="status" name="status" required class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">
            <option value="aktif" @selected(old('status', $product->status ?? 'aktif') === 'aktif')>Aktif — dapat dipesan</option>
            <option value="nonaktif" @selected(old('status', $product->status) === 'nonaktif')>Nonaktif — tidak dapat dipesan</option>
        </select>
        @error('status') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div class="space-y-2 md:col-span-2">
        <label for="deskripsi" class="text-sm font-semibold text-stone-700">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 focus:border-amber-400 focus:outline-none">{{ old('deskripsi', $product->deskripsi) }}</textarea>
        @error('deskripsi') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col-reverse justify-end gap-3 sm:flex-row md:col-span-2">
        <a href="{{ route('supplier.katalog') }}" class="rounded-xl border border-stone-200 px-5 py-2.5 text-center text-sm font-semibold text-stone-700 hover:bg-stone-50">Batal</a>
        <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-stone-900 hover:bg-amber-400">{{ $isEditing ? 'Simpan Perubahan' : 'Tambah ke Katalog' }}</button>
    </div>
</form>
