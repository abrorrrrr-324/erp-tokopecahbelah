@extends('layouts.dashboard')

@section('title', 'Dashboard Kepala Gudang')
@section('page_kicker', 'Gudang')
@section('page_title', 'Dashboard Kepala Gudang')

@section('content')
    <section class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ([
            ['title' => 'Total Jenis Barang', 'value' => number_format($stats['totalProduk'], 0, ',', '.'), 'tone' => 'text-stone-900'],
            ['title' => 'Total Stok Tersedia', 'value' => number_format($stats['totalStok'], 0, ',', '.'), 'tone' => 'text-stone-900'],
            ['title' => 'Barang Masuk Hari Ini', 'value' => number_format($stats['barangMasukHariIni'], 0, ',', '.'), 'tone' => 'text-emerald-700'],
            ['title' => 'Barang Keluar Hari Ini', 'value' => number_format($stats['barangKeluarHariIni'], 0, ',', '.'), 'tone' => 'text-amber-700'],
            ['title' => 'Total Barang Rusak', 'value' => number_format($stats['totalBarangRusak'], 0, ',', '.'), 'tone' => 'text-red-700'],
            ['title' => 'Menunggu Verifikasi', 'value' => number_format($stats['menungguVerifikasi'], 0, ',', '.'), 'tone' => 'text-orange-700'],
        ] as $stat)
            <article class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">{{ $stat['title'] }}</p>
                <p class="mt-3 text-3xl font-black {{ $stat['tone'] }}">{{ $stat['value'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="mt-6 grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-stone-900">Grafik Barang Masuk dan Keluar</h2>
                    <p class="text-sm text-stone-500">Jumlah unit selama 7 hari terakhir</p>
                </div>
                <div class="flex gap-4 text-xs font-semibold">
                    <span class="inline-flex items-center gap-2 text-emerald-700"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Masuk</span>
                    <span class="inline-flex items-center gap-2 text-amber-700"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>Keluar</span>
                </div>
            </div>

            <div class="space-y-4">
                @foreach ($chart as $day)
                    <div class="grid grid-cols-[3.5rem_1fr] items-center gap-3">
                        <span class="text-xs font-semibold text-stone-500">{{ $day['label'] }}</span>
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <div class="h-3 flex-1 overflow-hidden rounded-full bg-stone-100">
                                    <div class="h-full rounded-full bg-emerald-500" style="width: {{ ($day['masuk'] / $chartMaximum) * 100 }}%"></div>
                                </div>
                                <span class="w-10 text-right text-xs text-stone-600">{{ $day['masuk'] }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="h-3 flex-1 overflow-hidden rounded-full bg-stone-100">
                                    <div class="h-full rounded-full bg-amber-500" style="width: {{ ($day['keluar'] / $chartMaximum) * 100 }}%"></div>
                                </div>
                                <span class="w-10 text-right text-xs text-stone-600">{{ $day['keluar'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </article>

        <article class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-stone-900">Akses Cepat</h2>
                    <p class="text-sm text-stone-500">Kelola aktivitas gudang</p>
                </div>
            </div>
            <div class="mt-5 grid gap-3">
                <a href="{{ route('gudang.penerimaan') }}" class="rounded-2xl bg-amber-50 p-4 font-semibold text-amber-900 hover:bg-amber-100">Periksa penerimaan supplier</a>
                <a href="{{ route('gudang.stok.mutasi.create') }}" class="rounded-2xl bg-stone-50 p-4 font-semibold text-stone-800 hover:bg-stone-100">Catat stok masuk / keluar</a>
                <a href="{{ route('gudang.audit.create') }}" class="rounded-2xl bg-stone-50 p-4 font-semibold text-stone-800 hover:bg-stone-100">Catat barang rusak / hilang</a>
                <a href="{{ route('gudang.laporan') }}" class="rounded-2xl bg-stone-50 p-4 font-semibold text-stone-800 hover:bg-stone-100">Buka laporan gudang</a>
            </div>
        </article>
    </section>

    <section class="mt-6 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-stone-900">Aktivitas Terbaru</h2>
                <p class="text-sm text-stone-500">Mutasi stok dan catatan audit terbaru.</p>
            </div>
            <a href="{{ route('gudang.stok.mutasi') }}" class="text-sm font-semibold text-amber-700 hover:text-amber-800">Lihat riwayat mutasi</a>
        </div>

        @if ($activities->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-4 py-10 text-center text-sm text-stone-500">Belum ada aktivitas gudang.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-stone-50 text-xs uppercase tracking-wide text-stone-500">
                        <tr><th class="px-4 py-3">Tanggal</th><th class="px-4 py-3">Barang</th><th class="px-4 py-3">Aktivitas</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Petugas</th><th class="px-4 py-3">Catatan</th></tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @foreach ($activities as $activity)
                            <tr>
                                <td class="whitespace-nowrap px-4 py-4 text-stone-600">{{ $activity['date']?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-4 py-4 font-semibold text-stone-900">{{ $activity['product'] }}</td>
                                <td class="px-4 py-4">{{ $activity['type'] }}</td>
                                <td class="px-4 py-4">{{ $activity['quantity'] }}</td>
                                <td class="px-4 py-4">{{ $activity['person'] }}</td>
                                <td class="max-w-xs px-4 py-4 text-stone-600">{{ $activity['notes'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
