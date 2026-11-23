<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Gudang</title>
    <style>
        body { color: #292524; font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { font-size: 20px; margin: 0 0 4px; }
        h2 { font-size: 14px; margin: 22px 0 8px; }
        p { color: #57534e; margin: 4px 0 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #d6d3d1; padding: 6px; text-align: left; vertical-align: top; }
        th { background: #f5f5f4; }
        .print-button { background: #292524; border: 0; color: white; cursor: pointer; margin-bottom: 16px; padding: 8px 14px; }
        @media print { .print-button { display: none; } }
    </style>
</head>
<body>
    @if ($showPrintButton ?? false)
        <button class="print-button" onclick="window.print()">Cetak Laporan</button>
    @endif
    <h1>Laporan Stok dan Mutasi Gudang</h1>
    <p>Dibuat {{ $generatedAt->format('d/m/Y H:i') }}</p>

    <h2>Stok Saat Ini ({{ $stocks->count() }} jenis)</h2>
    <table>
        <thead><tr><th>SKU</th><th>Barang</th><th>Kategori</th><th>Stok</th><th>Minimum</th><th>Satuan</th></tr></thead>
        <tbody>
            @forelse ($stocks as $product)
                <tr>
                    <td>{{ $product->sku }}</td><td>{{ $product->nama }}</td><td>{{ $product->category?->nama ?? '-' }}</td>
                    <td>{{ $product->stok }}</td><td>{{ $product->stok_minimum ?? 10 }}</td><td>{{ $product->satuan }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Tidak ada data stok.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Mutasi Barang ({{ $movements->count() }} transaksi)</h2>
    <table>
        <thead><tr><th>Tanggal</th><th>SKU</th><th>Barang</th><th>Kategori</th><th>Jenis</th><th>Jumlah</th><th>Petugas</th><th>Catatan</th></tr></thead>
        <tbody>
            @forelse ($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at?->format('d/m/Y H:i') }}</td><td>{{ $movement->produk?->sku }}</td>
                    <td>{{ $movement->produk?->nama }}</td><td>{{ $movement->produk?->category?->nama }}</td>
                    <td>{{ $movement->jenis }}</td><td>{{ $movement->jumlah }}</td><td>{{ $movement->user?->name }}</td><td>{{ $movement->catatan }}</td>
                </tr>
            @empty
                <tr><td colspan="8">Tidak ada mutasi sesuai filter.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Audit Barang Rusak / Hilang ({{ $audits->count() }} catatan)</h2>
    <table>
        <thead><tr><th>Tanggal</th><th>SKU</th><th>Barang</th><th>Kategori</th><th>Jenis</th><th>Jumlah</th><th>Status</th><th>Penyebab</th><th>Tindak Lanjut</th></tr></thead>
        <tbody>
            @forelse ($audits as $audit)
                <tr>
                    <td>{{ $audit->created_at?->format('d/m/Y H:i') }}</td><td>{{ $audit->produk?->sku }}</td>
                    <td>{{ $audit->produk?->nama }}</td><td>{{ $audit->produk?->category?->nama }}</td>
                    <td>{{ $audit->jenis }}</td><td>{{ $audit->jumlah }}</td><td>{{ $audit->status }}</td>
                    <td>{{ $audit->penyebab }}</td><td>{{ $audit->tindak_lanjut }}</td>
                </tr>
            @empty
                <tr><td colspan="9">Tidak ada audit sesuai filter.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
