<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SupplierCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $supplier = $this->supplierFor($request->user());
        $products = $supplier->products()
            ->with('category')
            ->withCount('purchaseOrderItems')
            ->latest('updated_at')
            ->latest('id')
            ->get();

        return view('supplier.katalog.index', compact('products'));
    }

    public function create(): View
    {
        return view('supplier.katalog.create', [
            'product' => new SupplierProduct,
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $supplier = $this->supplierFor($request->user());
        $validated = $request->validate($this->productRules($supplier));

        $supplier->products()->create($validated);

        return redirect()->route('supplier.katalog')->with('success', 'Produk katalog berhasil ditambahkan.');
    }

    public function edit(Request $request, int $supplierProduct): View
    {
        $product = $this->productFor($request->user(), $supplierProduct);

        return view('supplier.katalog.edit', [
            'product' => $product,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, int $supplierProduct): RedirectResponse
    {
        $supplier = $this->supplierFor($request->user());
        $product = $supplier->products()->findOrFail($supplierProduct);
        $validated = $request->validate($this->productRules($supplier, $product));

        $product->update($validated);

        return redirect()->route('supplier.katalog')->with('success', 'Produk katalog berhasil diperbarui.');
    }

    public function destroy(Request $request, int $supplierProduct): RedirectResponse
    {
        $product = $this->productFor($request->user(), $supplierProduct);

        if ($product->purchaseOrderItems()->exists()) {
            return redirect()->route('supplier.katalog')->with('error', 'Produk tidak dapat dihapus karena sudah digunakan pada pesanan pembelian.');
        }

        $product->delete();

        return redirect()->route('supplier.katalog')->with('success', 'Produk katalog berhasil dihapus.');
    }

    private function supplierFor(User $user): Supplier
    {
        return Supplier::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['nama' => $user->name, 'email' => $user->email],
        );
    }

    private function productFor(User $user, int $productId): SupplierProduct
    {
        return $this->supplierFor($user)->products()->findOrFail($productId);
    }

    private function categories(): Collection
    {
        return ProductCategory::query()
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();
    }

    private function productRules(Supplier $supplier, ?SupplierProduct $product = null): array
    {
        $skuRule = Rule::unique('produk', 'sku')
            ->where(fn (QueryBuilder $query) => $query->where('supplier_id', $supplier->id));

        if ($product !== null) {
            $skuRule->ignore($product->id);
        }

        return [
            'sku' => [
                'bail',
                'required',
                'string',
                'max:50',
                $skuRule,
            ],
            'nama' => ['required', 'string', 'max:150'],
            'category_id' => ['bail', 'required', 'integer', 'exists:kategori_produk,id'],
            'deskripsi' => ['nullable', 'string'],
            'harga_beli' => ['bail', 'required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'satuan' => ['required', 'string', 'max:30'],
            'stok' => ['bail', 'required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
        ];
    }
}
