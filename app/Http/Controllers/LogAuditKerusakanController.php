<?php

namespace App\Http\Controllers;

use App\Models\LogAuditKerusakan;
use App\Models\Product;
use App\Models\PergerakanStok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LogAuditKerusakanController extends Controller
{
    public function index()
    {
        $logs = LogAuditKerusakan::with(['produk', 'user'])
            ->latest()
            ->paginate(10);

        return view('gudang.log-audit.index', compact('logs'));
    }

    public function create()
    {
        $produks = Product::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('gudang.log-audit.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jumlah_rusak' => 'required|integer|min:1',
            'tanggal_rusak' => 'required|date',
            'keterangan_kerusakan' => 'required|string|max:2000',
            'foto_bukti' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $foto = null;

        if ($request->hasFile('foto_bukti')) {
            $foto = $request->file('foto_bukti')
                ->store('log-audit-kerusakan', 'public');
        }

        try {
            DB::transaction(function () use ($validated, $foto) {

                // Ambil produk dan kunci baris agar stok tidak berubah
                // oleh transaksi lain selama proses pencatatan.
                $produk = Product::whereKey($validated['produk_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Pastikan jumlah barang rusak tidak melebihi stok.
                if ($validated['jumlah_rusak'] > $produk->stok) {
                    throw ValidationException::withMessages([
                        'jumlah_rusak' => 'Jumlah barang rusak tidak boleh melebihi stok yang tersedia.',
                    ]);
                }

                // Simpan log audit kerusakan.
                $log = LogAuditKerusakan::create([
                    'produk_id' => $validated['produk_id'],
                    'jumlah_rusak' => $validated['jumlah_rusak'],
                    'tanggal_rusak' => $validated['tanggal_rusak'],
                    'keterangan_kerusakan' => $validated['keterangan_kerusakan'],
                    'foto_bukti' => $foto,
                    'user_id' => Auth::id(),
                ]);

                // Kurangi stok produk sesuai jumlah barang rusak.
                $produk->decrement('stok', $validated['jumlah_rusak']);

                // Catat pergerakan stok keluar.
                PergerakanStok::create([
                    'produk_id' => $produk->id,
                    'jenis' => 'keluar',
                    'jumlah' => $validated['jumlah_rusak'],
                    'referensi_tipe' => 'log_audit_kerusakan',
                    'referensi_id' => $log->id,
                    'user_id' => Auth::id(),
                    'catatan' => 'Pengurangan stok karena kerusakan: '
                        . $validated['keterangan_kerusakan'],
                ]);
            });

        } catch (\Throwable $e) {

            // Hapus foto jika transaksi database gagal.
            if ($foto) {
                Storage::disk('public')->delete($foto);
            }

            throw $e;
        }

        return redirect()
            ->route('gudang.log-audit.index')
            ->with('success', 'Log kerusakan berhasil dicatat dan stok produk telah dikurangi.');
    }
}
