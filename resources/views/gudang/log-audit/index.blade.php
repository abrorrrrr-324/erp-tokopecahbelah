@extends('layouts.dashboard')

@section('title', 'Log Audit Kerusakan')
@section('page_kicker', 'Kepala Gudang')
@section('page_title', 'Log Audit Kerusakan')

@section('content')
    <section class="mt-8 rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">

        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-stone-900">
                    Riwayat Kerusakan Barang
                </h2>
                <p class="text-sm text-stone-500">
                    Daftar pencatatan barang rusak beserta bukti kerusakannya.
                </p>
            </div>

            <a href="{{ route('gudang.log-audit.create') }}"
                class="inline-flex w-fit items-center gap-2 rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                <span>+</span>
                Tambah Log Kerusakan
            </a>
        </div>

        @if (session('success'))
            <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div
            class="mb-6 inline-flex items-center gap-2 rounded-xl bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-800">
            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
            {{ $logs->total() }} Catatan Kerusakan
        </div>

        @if ($logs->isEmpty())
            <div class="rounded-2xl border border-dashed border-stone-300 bg-stone-50 px-4 py-12 text-center">
                <div class="mb-3 text-4xl">📋</div>
                <h3 class="text-lg font-bold text-stone-800">
                    Belum Ada Catatan Kerusakan
                </h3>
                <p class="mt-2 text-sm text-stone-500">
                    Catatan kerusakan barang yang ditambahkan akan muncul di halaman ini.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full min-w-[950px] text-left">
                    <thead>
                        <tr class="border-b border-stone-200 bg-stone-50 text-xs uppercase tracking-wider text-stone-500">
                            <th class="px-4 py-4 font-semibold">Produk</th>
                            <th class="px-4 py-4 font-semibold">Jumlah Rusak</th>
                            <th class="px-4 py-4 font-semibold">Tanggal Kerusakan</th>
                            <th class="px-4 py-4 font-semibold">Keterangan</th>
                            <th class="px-4 py-4 font-semibold">Bukti Foto</th>
                            <th class="px-4 py-4 font-semibold">Dicatat Oleh</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-stone-100">
                        @foreach ($logs as $log)
                            <tr class="transition hover:bg-stone-50">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-stone-800">
                                        {{ $log->produk->nama ?? 'Produk tidak ditemukan' }}
                                    </p>
                                </td>

                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex rounded-lg bg-red-50 px-3 py-1.5 text-sm font-semibold text-red-700">
                                        {{ $log->jumlah_rusak }} unit
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-sm text-stone-600">
                                    {{ $log->tanggal_rusak ? \Carbon\Carbon::parse($log->tanggal_rusak)->format('d/m/Y') : '-' }}
                                </td>

                                <td class="max-w-xs px-4 py-4 text-sm text-stone-600">
                                    {{ $log->keterangan_kerusakan }}
                                </td>

                                <td class="px-4 py-4">
                                    @if ($log->foto_bukti)
                                        <a href="{{ asset('storage/' . $log->foto_bukti) }}" target="_blank"
                                            class="font-semibold text-amber-700 hover:text-amber-900">
                                            Lihat Foto
                                        </a>
                                    @else
                                        <span class="text-sm text-stone-400">Tidak ada foto</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-sm text-stone-600">
                                    {{ $log->user->name ?? 'Tidak diketahui' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @endif
    </section>
@endsection
