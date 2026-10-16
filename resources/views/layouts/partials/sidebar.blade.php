@props(['menu' => [], 'config' => []])

<aside class="{{ $config['sidebar_class'] ?? 'bg-stone-900 text-stone-200' }} w-full max-w-[280px]">
    <div class="flex items-center gap-3 border-b border-white/10 px-6 py-5">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $config['brand_badge_class'] ?? 'bg-amber-400 text-stone-900' }} text-sm font-black">
            TP
        </div>
        <div>
            <p class="text-lg font-bold text-white">Toko Pecah Belah</p>
            <p class="text-xs {{ $config['profile_label_class'] ?? 'text-stone-400' }}">{{ $config['label'] ?? 'Dashboard' }}</p>
        </div>
    </div>

    <nav class="space-y-2 px-4 py-5">
        @foreach ($menu as $item)
            @php
                $href = $item['route'] ? route($item['route']) : '#';
                $classes = $item['active'] ?? false
                    ? ($config['nav_active_class'] ?? 'bg-amber-500 text-stone-900')
                    : ($config['nav_inactive_class'] ?? 'text-stone-300 hover:bg-stone-800 hover:text-white');
            @endphp

            <a
                href="{{ $href }}"
                class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium {{ $classes }}"
            >
                <span>{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="mt-8 border-t border-white/10 p-4">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="button" id="logout-trigger" class="w-full rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $config['button_class'] ?? 'bg-amber-500 text-stone-900 hover:bg-amber-400' }}">
                Logout
            </button>
        </form>
    </div>
</aside>

<div id="logout-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-stone-950/60 p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl">
        <p class="text-lg font-semibold text-stone-900">Apakah anda yakin ingin logout?</p>

        <div class="mt-6 flex justify-end gap-3">
            <button type="button" id="cancel-logout" class="rounded-xl border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 transition hover:bg-stone-100">
                Tidak
            </button>
            <button type="button" id="confirm-logout" class="rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-stone-900 transition hover:bg-amber-400">
                Yakin
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('logout-modal');
        const trigger = document.getElementById('logout-trigger');
        const cancelBtn = document.getElementById('cancel-logout');
        const confirmBtn = document.getElementById('confirm-logout');
        const form = document.getElementById('logout-form');

        if (!modal || !trigger || !cancelBtn || !confirmBtn || !form) {
            return;
        }

        trigger.addEventListener('click', function (event) {
            event.preventDefault();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        cancelBtn.addEventListener('click', function () {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        confirmBtn.addEventListener('click', function () {
            form.submit();
        });
    });
</script>
