@props(['config' => [], 'user' => null])

<header class="border-b border-stone-200 bg-white/90 backdrop-blur-sm">
    <div class="flex flex-col gap-4 p-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] {{ $config['section_label_class'] ?? 'text-amber-600' }}">
                @yield('page_kicker', $config['label'] ?? 'Dashboard')
            </p>
            <h1 class="mt-2 text-3xl font-black text-stone-900">
                @yield('page_title', 'Dashboard')
            </h1>
        </div>

        <div class="rounded-2xl border border-stone-200 bg-stone-50 px-4 py-2 text-sm text-stone-600">
            Selamat datang, <span class="font-semibold text-stone-900">{{ $user?->name ?? 'Pengguna' }}</span>
        </div>
    </div>
</header>
