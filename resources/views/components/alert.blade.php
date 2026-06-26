@props(['type' => 'success'])

@php
    $styles = match ($type) {
        'error' => [
            'container' => 'border-red-200/80 bg-red-50 text-red-800 dark:border-red-800/60 dark:bg-red-900/20 dark:text-red-300',
            'icon' => 'text-red-500 dark:text-red-400',
            'iconBg' => 'bg-red-100 dark:bg-red-900/40',
        ],
        default => [
            'container' => 'border-emerald-200/80 bg-emerald-50 text-emerald-800 dark:border-emerald-800/60 dark:bg-emerald-900/20 dark:text-emerald-300',
            'icon' => 'text-emerald-600 dark:text-emerald-400',
            'iconBg' => 'bg-emerald-100 dark:bg-emerald-900/40',
        ],
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-xl border px-4 py-3.5 text-sm shadow-sm '.$styles['container']]) }} role="alert">
    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $styles['iconBg'] }}">
        @if ($type === 'error')
            <svg class="h-4 w-4 {{ $styles['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        @else
            <svg class="h-4 w-4 {{ $styles['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        @endif
    </div>
    <div class="flex-1 pt-0.5 font-medium">{{ $slot }}</div>
</div>
