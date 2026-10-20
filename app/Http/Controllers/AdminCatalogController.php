<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminCatalogController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->latest('updated_at')
            ->latest('id')
            ->get();

        $totalProduk = $products->count();
        $stokRendah = $products->where('stock', '<=', 10)->count();
        $kategori = $products->pluck('category')->filter()->unique()->count();
        $penjualanBulanIni = $products->sum('price');

        return view('admin.katalog.index', compact(
            'products',
            'totalProduk',
            'stokRendah',
            'kategori',
            'penjualanBulanIni',
        ));
    }

    public function create(): View
    {
        return view('admin.katalog.create', [
            'product' => new Product,
        ]);
    }

    public function show(Product $product): View
    {
        return view('admin.katalog.show', [
            'product' => $product,
        ]);
    }

    public function gudangIndex(): View
    {
        return $this->catalogIndexView('kepala_gudang');
    }

    public function gudangShow(Product $product): View
    {
        return $this->catalogDetailView($product, 'kepala_gudang');
    }

    public function customerIndex(): View
    {
        return $this->catalogIndexView('customer');
    }

    public function customerShow(Product $product): View
    {
        return $this->catalogDetailView($product, 'customer');
    }

    public function edit(Product $product): View
    {
        return view('admin.katalog.edit', [
            'product' => $product,
        ]);
    }

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

        $validated['sku'] = $validated['sku'] ?? 'PRD-'.strtoupper(substr(sha1((string) now()->timestamp.random_int(1000, 9999)), 0, 8));
        $validated['created_by'] = auth()->user()?->id;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil ditambahkan.');
    }

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

        if ($request->hasFile('photo')) {
            $this->deletePhotoIfExists($product->photo);
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        $validated['sku'] = $validated['sku'] ?? ($product->sku ?? 'PRD-'.strtoupper(substr(sha1((string) now()->timestamp.random_int(1000, 9999)), 0, 8)));

        $product->update($validated);

        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deletePhotoIfExists($product->photo);
        $product->delete();

        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil dihapus.');
    }

    protected function deletePhotoIfExists(?string $photoPath): void
    {
        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            Storage::disk('public')->delete($photoPath);
        }
    }

    protected function catalogIndexView(string $role): View
    {
        $products = Product::query()
            ->latest('updated_at')
            ->latest('id')
            ->get();

        return view('catalog.index', [
            'products' => $products,
            'role' => $role,
        ]);
    }

    protected function catalogDetailView(Product $product, string $role): View
    {
        return view('catalog.detail', [
            'product' => $product,
            'role' => $role,
            'backRoute' => $role === 'customer' ? route('customer.katalog') : route('gudang.katalog'),
        ]);
    }
}
