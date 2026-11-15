<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\SupplierProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function index(): View
    {
        $orders = PurchaseOrder::query()
            ->with(['supplier.user', 'items.product'])
            ->latest('tanggal_pesanan')
            ->latest('id')
            ->paginate(15);

        $stats = [
            'ordersThisMonth' => PurchaseOrder::query()
                ->whereMonth('tanggal_pesanan', now()->month)
                ->whereYear('tanggal_pesanan', now()->year)
                ->count(),
            'waiting' => PurchaseOrder::query()->where('status', 'menunggu')->count(),
            'valueThisMonth' => PurchaseOrder::query()
                ->whereMonth('tanggal_pesanan', now()->month)
                ->whereYear('tanggal_pesanan', now()->year)
                ->sum('total'),
            'activeSuppliers' => Supplier::query()
                ->where('status', 'aktif')
                ->whereHas('user')
                ->count(),
        ];

        return view('admin.pembelian.index', compact('orders', 'stats'));
    }

    public function create(): View
    {
        $suppliers = Supplier::query()
            ->where('status', 'aktif')
            ->whereHas('user')
            ->with('user:id,name,email')
            ->orderBy('nama')
            ->get();

        $products = SupplierProduct::query()
            ->where('status', 'aktif')
            ->whereHas('supplier', fn (Builder $query) => $query->where('status', 'aktif')->whereHas('user'))
            ->with('supplier:id,nama')
            ->orderBy('nama')
            ->get();

        return view('admin.pembelian.create', compact('suppliers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $rawSupplierId = $request->input('supplier_id');
        $parsedSupplierId = is_scalar($rawSupplierId)
            ? filter_var((string) $rawSupplierId, FILTER_VALIDATE_INT)
            : false;
        $catalogSupplierId = $parsedSupplierId !== false && $parsedSupplierId > 0
            ? $parsedSupplierId
            : 0;

        $validated = $request->validate([
            'supplier_id' => [
                'bail',
                'required',
                'integer',
                Rule::exists('supplier', 'id')->where(
                    fn (QueryBuilder $query) => $query->where('status', 'aktif')->whereNotNull('user_id'),
                ),
            ],
            'catatan' => ['nullable', 'string', 'max:2000'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*' => ['required', 'array:product_id,jumlah'],
            'items.*.product_id' => [
                'bail',
                'required',
                'integer',
                'distinct',
                Rule::exists('produk', 'id')->where(
                    fn (QueryBuilder $query) => $query
                        ->where('supplier_id', $catalogSupplierId)
                        ->where('status', 'aktif'),
                ),
            ],
            'items.*.jumlah' => ['bail', 'required', 'integer', 'min:1', 'max:1000000'],
        ], [
            'supplier_id.exists' => 'Supplier tidak ditemukan atau tidak aktif.',
            'items.required' => 'Tambahkan minimal satu produk ke pesanan.',
            'items.*.product_id.exists' => 'Produk tidak tersedia pada katalog supplier yang dipilih.',
            'items.*.product_id.distinct' => 'Produk yang sama hanya boleh dicantumkan satu kali.',
            'items.*.jumlah.min' => 'Jumlah pesanan minimal satu.',
        ]);

        $order = DB::transaction(function () use ($validated, $request): PurchaseOrder {
            $productIds = collect($validated['items'])->pluck('product_id');
            $supplierProducts = SupplierProduct::query()
                ->where('supplier_id', $validated['supplier_id'])
                ->where('status', 'aktif')
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($supplierProducts->count() !== $productIds->count()) {
                throw ValidationException::withMessages([
                    'items' => 'Katalog supplier berubah. Periksa kembali produk yang dipilih.',
                ]);
            }

            $total = 0;
            foreach ($validated['items'] as $item) {
                $total += round((float) $supplierProducts[$item['product_id']]->harga_beli * $item['jumlah'], 2);
            }

            $order = PurchaseOrder::query()->create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => $request->user()->id,
                'tanggal_pesanan' => now(),
                'total' => $total,
                'status' => 'menunggu',
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $product = $supplierProducts[$item['product_id']];
                $subtotal = round((float) $product->harga_beli * $item['jumlah'], 2);

                $order->items()->create([
                    'produk_id' => $product->id,
                    'jumlah' => $item['jumlah'],
                    'harga' => $product->harga_beli,
                    'sub_total' => $subtotal,
                ]);
            }

            return $order;
        });

        return redirect()
            ->route('admin.pembelian.show', $order)
            ->with('success', 'Pesanan pembelian berhasil dibuat.');
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $purchaseOrder->load(['supplier.user', 'user', 'items.product.category']);

        return view('admin.pembelian.show', ['order' => $purchaseOrder]);
    }

    public function supplierDashboard(Request $request): View
    {
        $supplier = $this->supplierFor($request->user());
        $orders = $supplier->purchaseOrders()
            ->with('items.product')
            ->latest('tanggal_pesanan')
            ->latest('id')
            ->limit(5)
            ->get();

        $stats = [
            'orders' => $supplier->purchaseOrders()->count(),
            'waiting' => $supplier->purchaseOrders()->where('status', 'menunggu')->count(),
            'products' => $supplier->products()->count(),
            'activeProducts' => $supplier->products()->where('status', 'aktif')->count(),
        ];

        return view('supplier.dashboard', compact('orders', 'stats'));
    }

    public function supplierIndex(Request $request): View
    {
        $supplier = $this->supplierFor($request->user());
        $orders = $supplier->purchaseOrders()
            ->with(['user', 'items.product'])
            ->latest('tanggal_pesanan')
            ->latest('id')
            ->paginate(15);

        return view('supplier.pesanan.index', compact('orders'));
    }

    public function supplierShow(Request $request, PurchaseOrder $purchaseOrder): View
    {
        $supplier = $this->supplierFor($request->user());
        $order = $supplier->purchaseOrders()
            ->with(['user', 'items.product'])
            ->findOrFail($purchaseOrder->id);

        return view('supplier.pesanan.show', compact('order'));
    }

    public function supplierConfirm(Request $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $supplier = $this->supplierFor($request->user());
        $confirmed = DB::transaction(function () use ($supplier, $purchaseOrder): bool {
            $order = $supplier->purchaseOrders()
                ->lockForUpdate()
                ->findOrFail($purchaseOrder->id);

            if ($order->status !== 'menunggu') {
                return false;
            }

            $order->update(['status' => 'dikonfirmasi']);

            return true;
        });

        if (! $confirmed) {
            return redirect()
                ->route('supplier.pesanan.show', $purchaseOrder)
                ->with('error', 'Pesanan hanya dapat dikonfirmasi saat status menunggu.');
        }

        return redirect()
            ->route('supplier.pesanan.show', $purchaseOrder)
            ->with('success', 'Pesanan pembelian berhasil dikonfirmasi.');
    }

    private function supplierFor(User $user): Supplier
    {
        return Supplier::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['nama' => $user->name, 'email' => $user->email],
        );
    }
}
