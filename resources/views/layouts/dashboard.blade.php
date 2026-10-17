@php
    $user = auth()->user();
    $roleKey = strtolower(trim((string) ($user?->role ?? 'customer')));

    $roleKey = match ($roleKey) {
        'gudang', 'kepala_gudang', 'kepala gudang' => 'kepala_gudang',
        'costumer' => 'customer',
        'suplier' => 'supplier',
        default => $roleKey,
    };

    $menuByRole = [
        'admin' => [
            ['label' => 'Dashboard', 'icon' => '🏠', 'route' => 'admin.dashboard'],
            ['label' => 'Master Katalog', 'icon' => '📦', 'route' => 'admin.katalog'],
            ['label' => 'Customer', 'icon' => '👥', 'route' => 'admin.customer'],
            ['label' => 'Supplier', 'icon' => '🏭', 'route' => 'admin.supplier'],
            ['label' => 'Transaksi', 'icon' => '🧾', 'route' => 'admin.transaksi'],
            ['label' => 'Pembelian', 'icon' => '🛒', 'route' => 'admin.pembelian'],
            ['label' => 'Stok', 'icon' => '📊', 'route' => 'admin.stok'],
            ['label' => 'Laporan', 'icon' => '📈', 'route' => 'admin.laporan'],
        ],
        'kepala_gudang' => [
            ['label' => 'Dashboard', 'icon' => '🏠', 'route' => 'gudang.dashboard'],
            ['label' => 'Katalog Barang', 'icon' => '📦', 'route' => 'gudang.katalog'],
            ['label' => 'Real Stok', 'icon' => '📊', 'route' => null],
            ['label' => 'Kelola Stok', 'icon' => '🧮', 'route' => null],
            ['label' => 'Pengecekan', 'icon' => '🔍', 'route' => null],
            ['label' => 'Update Qty', 'icon' => '✏️', 'route' => null],
            ['label' => 'Audit Log', 'icon' => '📝', 'route' => null],
            ['label' => 'Mutasi Keluar', 'icon' => '🚚', 'route' => null],
            ['label' => 'Verifikasi Supplier', 'icon' => '✅', 'route' => null],
            ['label' => 'Laporan', 'icon' => '📈', 'route' => null],
        ],
        'supplier' => [
            ['label' => 'Dashboard', 'icon' => '🏠', 'route' => 'supplier.dashboard'],
            ['label' => 'Pesanan', 'icon' => '🧾', 'route' => null],
            ['label' => 'Detail Pesanan', 'icon' => '📄', 'route' => null],
            ['label' => 'Konfirmasi', 'icon' => '✅', 'route' => null],
            ['label' => 'Pengiriman', 'icon' => '🚚', 'route' => null],
            ['label' => 'Ketersediaan', 'icon' => '📦', 'route' => null],
            ['label' => 'Status', 'icon' => '📊', 'route' => null],
        ],
        'customer' => [
            ['label' => 'Dashboard', 'icon' => '🏠', 'route' => 'customer.dashboard'],
            ['label' => 'Katalog', 'icon' => '🛍️', 'route' => 'customer.katalog'],
            ['label' => 'Pesanan', 'icon' => '🧾', 'route' => null],
            ['label' => 'Status', 'icon' => '📊', 'route' => null],
            ['label' => 'Riwayat', 'icon' => '🕘', 'route' => null],
            ['label' => 'Informasi', 'icon' => '🔔', 'route' => null],
        ],
    ];

    $roleConfig = [
        'admin' => [
            'label' => 'Admin Toko',
            'sidebar_class' => 'bg-stone-900 text-stone-200',
            'brand_badge_class' => 'bg-amber-400 text-stone-900',
            'nav_active_class' => 'bg-amber-500 text-stone-900',
            'nav_inactive_class' => 'text-stone-300 hover:bg-stone-800 hover:text-white',
            'profile_class' => 'bg-stone-800',
            'profile_label_class' => 'text-stone-400',
            'button_class' => 'bg-amber-500 text-stone-900 hover:bg-amber-400',
            'section_label_class' => 'text-amber-600',
        ],
        'kepala_gudang' => [
            'label' => 'Kepala Gudang',
            'sidebar_class' => 'bg-stone-900 text-stone-200',
            'brand_badge_class' => 'bg-amber-400 text-stone-900',
            'nav_active_class' => 'bg-amber-500 text-stone-900',
            'nav_inactive_class' => 'text-stone-300 hover:bg-stone-800 hover:text-white',
            'profile_class' => 'bg-stone-800',
            'profile_label_class' => 'text-stone-400',
            'button_class' => 'bg-amber-500 text-stone-900 hover:bg-amber-400',
            'section_label_class' => 'text-amber-600',
        ],
        'supplier' => [
            'label' => 'Supplier',
            'sidebar_class' => 'bg-stone-900 text-stone-200',
            'brand_badge_class' => 'bg-amber-400 text-stone-900',
            'nav_active_class' => 'bg-amber-500 text-stone-900',
            'nav_inactive_class' => 'text-stone-300 hover:bg-stone-800 hover:text-white',
            'profile_class' => 'bg-stone-800',
            'profile_label_class' => 'text-stone-400',
            'button_class' => 'bg-amber-500 text-stone-900 hover:bg-amber-400',
            'section_label_class' => 'text-amber-600',
        ],
        'customer' => [
            'label' => 'Customer',
            'sidebar_class' => 'bg-stone-900 text-stone-200',
            'brand_badge_class' => 'bg-amber-400 text-stone-900',
            'nav_active_class' => 'bg-amber-500 text-stone-900',
            'nav_inactive_class' => 'text-stone-300 hover:bg-stone-800 hover:text-white',
            'profile_class' => 'bg-stone-800',
            'profile_label_class' => 'text-stone-400',
            'button_class' => 'bg-amber-500 text-stone-900 hover:bg-amber-400',
            'section_label_class' => 'text-amber-600',
        ],
    ];

    $menu = $menuByRole[$roleKey] ?? $menuByRole['customer'];
    $config = $roleConfig[$roleKey] ?? $roleConfig['customer'];

    $menu = array_map(function ($item) {
        $item['active'] = ! empty($item['route']) && request()->routeIs($item['route']);

        return $item;
    }, $menu);
@endphp

<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title', 'Dashboard')</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            }
        </style>
    </head>
    <body class="bg-stone-100 text-stone-800 antialiased">
        <div class="flex min-h-screen">
            @include('layouts.partials.sidebar', ['config' => $config, 'menu' => $menu])

            <div class="flex min-h-screen flex-1 flex-col">
                @include('layouts.partials.topbar', ['config' => $config, 'user' => $user])

                @if (session('success'))
                    <div class="mx-6 mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm lg:mx-8">
                        {{ session('success') }}
                    </div>
                @endif

                <main class="flex-1 p-6 lg:p-8">
                    @yield('content')
                </main>

                @include('layouts.partials.footer', ['config' => $config])
            </div>
        </div>
    </body>
</html>
