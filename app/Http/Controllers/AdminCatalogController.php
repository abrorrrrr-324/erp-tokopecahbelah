<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCatalogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
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
}
