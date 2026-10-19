@props(['config' => [], 'user' => null])

<header class="border-b border-stone-200 bg-white/90 backdrop-blur-sm">
    <div class="flex flex-col gap-4 p-5 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            @php
                $pageKicker = trim((string) view()->yieldContent('page_kicker'));
                $pageTitle = trim((string) view()->yieldContent('page_title'));
            @endphp

            @if ($pageKicker !== '')
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-stone-500">
                    {{ $pageKicker }}
                </p>
            @endif

            @if ($pageTitle !== '')
                <h1 class="mt-1 text-xl font-bold text-stone-900 tracking-tight">
                    {{ $pageTitle }}
                </h1>
            @endif
        </div>

        <div class="rounded-xl border border-stone-200 bg-stone-50 px-3 py-2 text-sm text-stone-600">
            Selamat datang, <span class="font-semibold text-stone-900">{{ $user?->name ?? 'Pengguna' }}</span>
        </div>
    </div>
</header>
