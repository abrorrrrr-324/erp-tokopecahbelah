@props(['product', 'isEditing'])

<form action="{{ $isEditing ? route('admin.katalog.update', $product) : route('admin.katalog.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 grid gap-5 md:grid-cols-2">
    @csrf
    @if ($isEditing) @method('PUT') @endif

    {{-- SKU --}}
    <div class="space-y-2">
        <label for="sku" class="text-sm font-medium text-stone-700">SKU</label>
        <input id="sku" name="sku" type="text" value="{{ old('sku', $product->sku ?? '') }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white" placeholder="Contoh: PRD-001">
        @error('sku') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Nama Produk --}}
    <div class="space-y-2">
        <label for="name" class="text-sm font-medium text-stone-700">Nama Produk</label>
        <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white" placeholder="Contoh: Kursi Kayu Minimalis" required>
        @error('name') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Kategori --}}
    <div class="space-y-2">
        <label for="category" class="text-sm font-medium text-stone-700">Kategori</label>
        <input id="category" name="category" type="text" value="{{ old('category', $product->category) }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white" placeholder="Contoh: Furniture" required>
        @error('category') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Satuan --}}
    <div class="space-y-2">
        <label for="unit" class="text-sm font-medium text-stone-700">Satuan</label>
        <input id="unit" name="unit" type="text" value="{{ old('unit', $product->unit ?? 'pcs') }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white" placeholder="Contoh: pcs, box, set">
        @error('unit') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Harga Beli --}}
    <div class="space-y-2">
        <label for="buy_price" class="text-sm font-medium text-stone-700">Harga Beli</label>
        <input id="buy_price" name="buy_price" type="number" min="0" value="{{ old('buy_price', $product->buy_price ?? $product->harga_beli ?? 0) }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white">
        @error('buy_price') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Harga Jual --}}
    <div class="space-y-2">
        <label for="price" class="text-sm font-medium text-stone-700">Harga Jual</label>
        <input id="price" name="price" type="number" min="0" value="{{ old('price', $product->price ?? $product->harga_jual ?? 0) }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white" required>
        @error('price') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Stok --}}
    <div class="space-y-2">
        <label for="stock" class="text-sm font-medium text-stone-700">Stok</label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? $product->stok ?? 0) }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white" required>
        @error('stock') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Stok Minimum --}}
    <div class="space-y-2">
        <label for="minimum_stock" class="text-sm font-medium text-stone-700">Stok Minimum</label>
        <input id="minimum_stock" name="minimum_stock" type="number" min="0" value="{{ old('minimum_stock', $product->minimum_stock ?? $product->stok_minimum ?? 0) }}" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white">
        @error('minimum_stock') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Deskripsi --}}
    <div class="space-y-2 md:col-span-2">
        <label for="description" class="text-sm font-medium text-stone-700">Deskripsi</label>
        <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white" placeholder="Masukkan deskripsi produk">{{ old('description', $product->description ?? $product->deskripsi ?? '') }}</textarea>
        @error('description') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Status --}}
    <div class="space-y-2 md:col-span-2">
        <label for="status" class="text-sm font-medium text-stone-700">Status</label>
        @php $selectedStatus = old('status', $product->status ?? 'Aktif'); @endphp
        <select id="status" name="status" class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-stone-900 outline-none transition focus:border-amber-400 focus:bg-white" required>
            @foreach (['Aktif', 'Promo', 'Hampir habis', 'Nonaktif'] as $status)
                <option value="{{ $status }}" @selected($selectedStatus === $status)>{{ $status }}</option>
            @endforeach
        </select>
        @error('status') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
    </div>

    {{-- Foto Produk --}}
    <div class="space-y-2 md:col-span-2">
        <label for="photo" class="text-sm font-medium text-stone-700">Foto Produk</label>
        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-dashed border-stone-300 bg-stone-50 px-3 py-2.5 text-stone-700 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-100 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-amber-700">
        @error('photo') <span class="text-xs text-red-600">{{ $message }}</span> @enderror

        @if ($product->exists && $product->photo)
            <div class="mt-3 flex items-center gap-3 rounded-2xl border border-stone-200 bg-stone-50 p-3">
                <img src="{{ \Illuminate\Support\Facades\Storage::url($product->photo) }}" alt="Foto {{ $product->name }}" class="h-16 w-16 rounded-xl object-cover">
                <span class="text-sm text-stone-600">Foto saat ini</span>
            </div>
        @endif
    </div>

    {{-- Tombol Aksi --}}
    <div class="flex justify-end gap-3 md:col-span-2">
        <a href="{{ route('admin.katalog') }}" class="rounded-xl border border-stone-200 px-5 py-2.5 text-sm font-semibold text-stone-700 hover:border-stone-300 hover:bg-stone-50">Batal</a>
        <button type="submit" class="rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
            {{ $isEditing ? 'Simpan Perubahan' : 'Tambah Produk' }}
        </button>
    </div>
</form>
