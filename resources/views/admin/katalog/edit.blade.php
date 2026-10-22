@extends('layouts.dashboard')

@section('title', 'Edit Produk')
@section('page_kicker', 'Admin')
@section('page_title', 'Edit Produk')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="rounded-3xl border border-stone-200 bg-white p-6 shadow-sm">
            <div class="mb-6">
                <p class="text-sm text-stone-500">Ubah data katalog produk</p>
                <h2 class="mt-1 text-2xl font-bold text-stone-900">Edit Produk</h2>
            </div>

            @include('admin.katalog._form', [
                'product' => $product,
                'isEditing' => true,
            ])
        </div>
    </div>

    {{-- Modal Konfirmasi Edit --}}
    <div id="edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-950/60 p-4" role="dialog" aria-modal="true" aria-labelledby="edit-modal-title">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
            <p id="edit-modal-title" class="text-lg font-semibold text-stone-900">
                Apakah anda yakin ingin mengubah data produk ini?
            </p>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="cancel-edit" class="rounded-xl border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-100">
                    Tidak
                </button>
                <button type="button" id="confirm-edit" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                    Iya
                </button>
            </div>
        </div>
    </div>

    {{-- JavaScript Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('edit-modal');
            const cancelBtn = document.getElementById('cancel-edit');
            const confirmBtn = document.getElementById('confirm-edit');
            const form = document.querySelector('form[action*="admin/katalog"]');

            if (!modal || !form) return;

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                cancelBtn.focus();
            });

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            cancelBtn.addEventListener('click', closeModal);

            confirmBtn.addEventListener('click', function () {
                form.submit();
            });

            modal.addEventListener('click', function (event) {
                if (event.target === modal) closeModal();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
@endsection
