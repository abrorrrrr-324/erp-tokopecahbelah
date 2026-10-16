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
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="w-full rounded-xl px-4 py-2.5 text-sm font-semibold transition {{ $config['button_class'] ?? 'bg-amber-500 text-stone-900 hover:bg-amber-400' }}">
                Logout
            </button>
        </form>
    </div>
</aside>
