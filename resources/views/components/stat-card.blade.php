@props([
    'title',
    'value',
    'trend' => null,
    'trendUp' => true,
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <p class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">{{ $value }}</p>
            @if ($trend)
                <p @class([
                    'mt-2 inline-flex items-center gap-1 text-xs font-medium',
                    'text-green-600 dark:text-green-400' => $trendUp,
                    'text-red-600 dark:text-red-400' => ! $trendUp,
                ])>
                    @if ($trendUp)
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    @else
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    @endif
                    {{ $trend }}
                </p>
            @endif
        </div>
        @isset($icon)
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400">
                {{ $icon }}
            </div>
        @endisset
    </div>
</div>
