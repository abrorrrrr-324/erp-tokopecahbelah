<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCatalogController extends Controller
{
    // ==================== KATALOG ADMIN ====================

    // Menampilkan produk dan statistik katalog
    public function index(): View
    {
        $search = request()->query('search');

        $query = Product::query();

        if ($search !== null && $search !== '') {
            $keyword = strtolower(trim((string) $search));

            $query->where(function ($query) use ($keyword) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $keyword . '%'])
                    ->orWhereRaw('LOWER(category) LIKE ?', ['%' . $keyword . '%'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%' . $keyword . '%']);
            });
        }

        $totalProduk = Product::count();
        $stokRendah = Product::where('stock', '<=', 10)->count();
        $kategori = Product::whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->count('category');

        $penjualanBulanIni = Product::sum('price');

        $products = $query
            ->latest('updated_at')
            ->latest('id')
            ->get();

        return view('admin.katalog.index', compact(
            'products',
            'totalProduk',
            'stokRendah',
            'kategori',
            'penjualanBulanIni',
        ));
    }

    // Form tambah produk
    public function create(): View
    {
        return view('admin.katalog.create', ['product' => new Product]);
    }

    // Detail produk untuk admin
    public function show(Product $product): View
    {
        return view('admin.katalog.show', ['product' => $product]);
    }

    // ==================== KATALOG GUDANG ====================

    // Daftar produk untuk kepala gudang
    public function gudangIndex(): View
    {
        return $this->catalogIndexView('kepala_gudang');
    }

    // Detail produk untuk kepala gudang
    public function gudangShow(Product $product): View
    {
        return $this->catalogDetailView($product, 'kepala_gudang');
    }

    // ==================== KATALOG CUSTOMER ====================

    // Daftar produk untuk customer
    public function customerIndex(): View
    {
        return $this->catalogIndexView('customer');
    }

    // Detail produk untuk customer
    public function customerShow(Product $product): View
    {
        return $this->catalogDetailView($product, 'customer');
    }

    // ==================== CRUD PRODUK ADMIN ====================

    // Form edit produk
    public function edit(Product $product): View
    {
        return view('admin.katalog.edit', ['product' => $product]);
    }

    // Validasi dan simpan produk baru beserta foto
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
            'buy_price' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'unit' => ['nullable', 'string', 'max:30'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Membuat SKU otomatis jika tidak diisi
        $validated['sku'] = $validated['sku'] ?? 'PRD-' . strtoupper(substr(sha1((string) now()->timestamp . random_int(1000, 9999)), 0, 8));
        $validated['created_by'] = auth()->user()?->id;

        // Upload foto ke storage public
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil ditambahkan.');
    }

    // Validasi dan perbarui produk
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
            'buy_price' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'unit' => ['nullable', 'string', 'max:30'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Hapus foto lama jika diganti dengan foto baru
        if ($request->hasFile('photo')) {
            $this->deletePhotoIfExists($product->photo);
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        // Pertahankan SKU lama jika input SKU kosong
        $validated['sku'] = $validated['sku'] ?? ($product->sku ?? 'PRD-' . strtoupper(substr(sha1((string) now()->timestamp . random_int(1000, 9999)), 0, 8)));

        $product->update($validated);

        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil diperbarui.');
    }

    // Hapus foto dan data produk
    public function destroy(Product $product): RedirectResponse
    {
        $this->deletePhotoIfExists($product->photo);
        $product->delete();

        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil dihapus.');
    }

    // ==================== FUNGSI BANTUAN ====================

    // Memeriksa keberadaan foto sebelum menghapusnya
    protected function deletePhotoIfExists(?string $photoPath): void
    {
        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            Storage::disk('public')->delete($photoPath);
        }
    }

    // Menampilkan daftar produk berdasarkan role
    protected function catalogIndexView(string $role): View
    {
        $search = request()->query('search');
        $query = Product::query();

        if ($search !== null && $search !== '') {
            $keyword = strtolower(trim((string) $search));

            $query->where(function ($query) use ($keyword) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $keyword . '%'])
                    ->orWhereRaw('LOWER(category) LIKE ?', ['%' . $keyword . '%'])
                    ->orWhereRaw('LOWER(status) LIKE ?', ['%' . $keyword . '%']);
            });
        }

        $products = $query
            ->latest('updated_at')
            ->latest('id')
            ->get();

        return view('catalog.index', [
            'products' => $products,
            'role' => $role,
        ]);
    }

    // Menampilkan detail produk dan menentukan route kembali
    protected function catalogDetailView(Product $product, string $role): View
    {
        return view('catalog.detail', [
            'product' => $product,
            'role' => $role,
            'backRoute' => $role === 'customer' ? route('customer.katalog') : route('gudang.katalog'),
        ]);
    }
}
