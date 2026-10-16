@props(['config' => []])

<footer class="border-t border-stone-200 bg-white px-6 py-4 text-sm text-stone-500">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <p>© {{ date('Y') }} Toko Pecah Belah</p>
        <span class="inline-flex items-center rounded-full bg-stone-100 px-3 py-1 text-xs font-semibold text-stone-600">
            {{ $config['label'] ?? 'Dashboard' }}
        </span>
    </div>
</footer>
