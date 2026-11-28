@extends('layouts.dashboard')

@section('title', 'Laporan Stok')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Laporan Stok Masuk & Keluar')

@section('content')
    <section class="mt-8 space-y-6">

        {{-- RINGKASAN --}}
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            <div class="rounded-3xl border border-green-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-stone-500">Total Stok Masuk</p>
                        <h2 class="mt-2 text-3xl font-bold text-green-700">
                            {{ number_format($totalMasuk, 0, ',', '.') }}
                        </h2>
                        <p class="mt-1 text-xs text-stone-500">Total unit barang masuk</p>
                    </div>
                    <div class="rounded-2xl bg-green-50 p-4 text-2xl">📥</div>
                </div>
            </div>

            <div class="rounded-3xl border border-red-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-stone-500">Total Stok Keluar</p>
                        <h2 class="mt-2 text-3xl font-bold text-red-700">
                            {{ number_format($totalKeluar, 0, ',', '.') }}
                        </h2>
                        <p class="mt-1 text-xs text-stone-500">Total unit barang keluar</p>
                    </div>
                    <div class="rounded-2xl bg-red-50 p-4 text-2xl">📤</div>
                </div>
            </div>

        </div>

        {{-- TABEL LAPORAN --}}
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">

            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-stone-900">
                        Riwayat Pergerakan Stok
                    </h2>
                    <p class="mt-1 text-sm text-stone-500">
                        Riwayat barang masuk dan keluar dari gudang.
                    </p>
                </div>

                <button onclick="window.print()"
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                    🖨️ Cetak Laporan
                </button>
            </div>

            {{-- FILTER --}}
            <form method="GET" action="{{ route('gudang.laporan-stok') }}"
                class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center">

                <label for="jenis" class="text-sm font-semibold text-stone-700">
                    Filter Pergerakan:
                </label>

                <select name="jenis" id="jenis"
                    class="rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-700 outline-none focus:border-amber-500"
                    onchange="this.form.submit()">

                    <option value="semua" {{ $jenis === 'semua' ? 'selected' : '' }}>
                        Semua Pergerakan
                    </option>

                    <option value="masuk" {{ $jenis === 'masuk' ? 'selected' : '' }}>
                        Stok Masuk
                    </option>

                    <option value="keluar" {{ $jenis === 'keluar' ? 'selected' : '' }}>
                        Stok Keluar
                    </option>
                </select>
            </form>

            {{-- TABEL --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                            <th class="px-4 py-4 font-semibold">Tanggal</th>
                            <th class="px-4 py-4 font-semibold">Nama Produk</th>
                            <th class="px-4 py-4 font-semibold">Jenis</th>
                            <th class="px-4 py-4 font-semibold">Jumlah</th>
                            <th class="px-4 py-4 font-semibold">Sumber Transaksi</th>
                            <th class="px-4 py-4 font-semibold">Dicatat Oleh</th>
                            <th class="px-4 py-4 font-semibold">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-100">
                        @forelse ($pergerakan as $item)
                            <tr class="transition hover:bg-stone-50">
                                <td class="px-4 py-4 text-sm text-stone-600">
                                    {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : '-' }}
                                </td>

                                <td class="px-4 py-4 font-semibold text-stone-800">
                                    {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                                </td>

                                <td class="px-4 py-4">
                                    @if ($item->jenis === 'masuk')
                                        <span
                                            class="rounded-lg bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700">
                                            Stok Masuk
                                        </span>
                                    @else
                                        <span class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700">
                                            Stok Keluar
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-sm font-bold text-stone-800">
                                    {{ number_format($item->jumlah, 0, ',', '.') }} unit
                                </td>

                                <td class="px-4 py-4 text-sm text-stone-600">
                                    {{ $item->referensi_tipe ?? '-' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-stone-600">
                                    {{ $item->user->name ?? 'Tidak diketahui' }}
                                </td>

                                <td class="max-w-xs px-4 py-4 text-sm text-stone-600">
                                    {{ $item->catatan ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center">
                                    <div class="text-4xl">📋</div>
                                    <h3 class="mt-3 font-bold text-stone-800">
                                        Belum Ada Riwayat Stok
                                    </h3>
                                    <p class="mt-1 text-sm text-stone-500">
                                        Data pergerakan stok akan muncul di halaman ini.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $pergerakan->links() }}
            </div>

        </div>
    </section>

    {{-- PENGATURAN CETAK --}}
    <style>
        @media print {

            aside,
            nav,
            header,
            button,
            form {
                display: none !important;
            }

            body {
                background: white !important;
            }

            main {
                margin: 0 !important;
                padding: 0 !important;
            }

            section {
                margin-top: 0 !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }
        }
    </style>
@endsection
