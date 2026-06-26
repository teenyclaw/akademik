@props([
    'title',
    'value',
    'trend' => null,
    'trendUp' => true,
    'color' => 'indigo',
])

@php
    $iconColors = match ($color) {
        'violet' => 'bg-violet-50 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400',
        'blue' => 'bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
        'emerald' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
        default => 'bg-brand-50 text-brand-600 dark:bg-brand-900/30 dark:text-brand-400',
    };
@endphp

<div {{ $attributes->merge(['class' => 'premium-card group p-6 transition-all duration-300 hover:shadow-premium-lg hover:-translate-y-0.5']) }}>
    <div class="flex items-start justify-between gap-4">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900 dark:text-white">{{ $value }}</p>
            @if ($trend)
                <p @class([
                    'mt-3 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold',
                    'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' => $trendUp,
                    'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' => ! $trendUp,
                ])>
                    @if ($trendUp)
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                    @else
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                    @endif
                    {{ $trend }}
                </p>
            @endif
        </div>
        @isset($icon)
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $iconColors }} transition-transform duration-300 group-hover:scale-110">
                {{ $icon }}
            </div>
        @endisset
    </div>
</div>
