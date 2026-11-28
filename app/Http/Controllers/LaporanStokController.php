<?php

namespace App\Http\Controllers;

use App\Models\PergerakanStok;
use Illuminate\Http\Request;

class LaporanStokController extends Controller
{
    public function index(Request $request)
    {
        $jenis = $request->query('jenis');

        $query = PergerakanStok::with(['produk', 'user'])
            ->orderByDesc('created_at');

        if (in_array($jenis, ['masuk', 'keluar'], true)) {
            $query->where('jenis', $jenis);
        } else {
            $jenis = 'semua';
        }

        $pergerakan = $query->paginate(10)->withQueryString();

        $totalMasuk = PergerakanStok::where('jenis', 'masuk')
            ->sum('jumlah');

        $totalKeluar = PergerakanStok::where('jenis', 'keluar')
            ->sum('jumlah');

        return view('gudang.laporan-stok.index', compact(
            'pergerakan',
            'totalMasuk',
            'totalKeluar',
            'jenis'
        ));
    }
}
