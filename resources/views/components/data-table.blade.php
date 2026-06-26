@props([
    'title' => null,
    'searchPlaceholder' => 'Cari...',
])

<div {{ $attributes->merge(['class' => 'premium-card overflow-hidden shadow-premium']) }}>
    <div class="flex flex-col gap-4 border-b border-slate-100 p-5 dark:border-slate-700/60 sm:flex-row sm:items-center sm:justify-between">
        @if ($title)
            <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            @isset($search)
                {{ $search }}
            @else
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="search"
                        placeholder="{{ $searchPlaceholder }}"
                        class="input-premium w-full pl-10 sm:w-64"
                    />
                </div>
            @endisset

            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    </div>

    <div class="overflow-x-auto">
        {{ $slot }}
    </div>

    @isset($pagination)
        <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-700/60">
            {{ $pagination }}
        </div>
    @endisset
</div>
