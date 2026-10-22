@extends('layouts.dashboard')

@section('title', 'Supplier')
@section('page_kicker', 'Admin')
@section('page_title', 'Data Supplier')

@section('content')
    <div class="space-y-6">
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Total Supplier</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['total'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Aktif</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['active'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Review</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['review'] }}</p>
            </div>
            <div class="rounded-3xl border border-stone-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-stone-500">Update Terakhir</p>
                <p class="mt-3 text-3xl font-black text-stone-900">{{ $stats['lastUpdated'] }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h3 class="text-xl font-bold text-stone-900">Daftar Mitra Supplier</h3>
                <a href="{{ route('admin.supplier.create') }}" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-stone-900 hover:bg-amber-400">
                    + Tambah Supplier
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm text-stone-600">
                    <thead>
                        <tr class="border-b border-stone-200 text-stone-500">
                            <th class="pb-3 font-semibold">Nama</th>
                            <th class="pb-3 font-semibold">Email</th>
                            <th class="pb-3 font-semibold">Dibuat</th>
                            <th class="pb-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr class="border-b border-stone-100 last:border-0">
                                <td class="py-3 pr-4 font-semibold text-stone-900">{{ $supplier->name }}</td>
                                <td class="py-3 pr-4">{{ $supplier->email }}</td>
                                <td class="py-3 pr-4">{{ $supplier->created_at?->translatedFormat('d M Y') ?? '-' }}</td>
                                <td class="py-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.supplier.show', $supplier) }}" class="font-semibold text-stone-600 hover:text-stone-500">Detail</a>
                                        <a href="{{ route('admin.supplier.edit', $supplier) }}" class="font-semibold text-amber-600 hover:text-amber-500">Edit</a>
                                        <form action="{{ route('admin.supplier.destroy', $supplier) }}" method="POST" data-delete-form data-product-name="{{ $supplier->name }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" data-delete-trigger class="font-semibold text-red-600 hover:text-red-500">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-sm text-stone-500">Belum ada supplier yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
            <p id="delete-modal-title" class="text-lg font-semibold text-stone-900">
                Apakah anda yakin ingin menghapus supplier {{ $suppliers->first()->name ?? '' }}?
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="cancel-delete" class="rounded-xl border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-100">Tidak</button>
                <button type="button" id="confirm-delete" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">Iya</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('delete-modal');
            const modalTitle = document.getElementById('delete-modal-title');
            const cancelBtn = document.getElementById('cancel-delete');
            const confirmBtn = document.getElementById('confirm-delete');
            let selectedForm = null;

            document.querySelectorAll('[data-delete-trigger]').forEach(button => {
                button.addEventListener('click', function () {
                    selectedForm = this.closest('[data-delete-form]');
                    if (!selectedForm) return;

                    modalTitle.textContent = 'Apakah anda yakin ingin menghapus supplier ' + selectedForm.dataset.productName + '?';
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    cancelBtn.focus();
                });
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                selectedForm = null;
            }

            cancelBtn.addEventListener('click', closeModal);

            confirmBtn.addEventListener('click', function () {
                if (selectedForm) selectedForm.submit();
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) closeModal();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });
        });
    </script>
@endsection
