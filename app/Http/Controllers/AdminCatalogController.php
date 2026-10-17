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

        return view('admin.katalog', compact(
            'products',
            'totalProduk',
            'stokRendah',
            'kategori',
            'penjualanBulanIni',
        ));
    }

    public function create(): View
    {
        return view('admin.katalog-form', [
            'product' => new Product,
            'isEditing' => false,
        ]);
    }

    public function edit(Product $product): View
    {
        return view('admin.katalog-form', [
            'product' => $product,
            'isEditing' => true,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

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
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'max:50'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $this->deletePhotoIfExists($product->photo);
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

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
}
