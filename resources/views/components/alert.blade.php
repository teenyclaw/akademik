@props(['type' => 'success'])

@php
    $styles = match ($type) {
        'error' => [
            'container' => 'border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300',
            'icon' => 'text-red-500 dark:text-red-400',
        ],
        default => [
            'container' => 'border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-300',
            'icon' => 'text-green-500 dark:text-green-400',
        ],
    };
@endphp

<div {{ $attributes->merge(['class' => 'flex items-start gap-3 rounded-lg border px-4 py-3 text-sm '.$styles['container']]) }} role="alert">
    @if ($type === 'error')
        <svg class="mt-0.5 h-5 w-5 shrink-0 {{ $styles['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    @else
        <svg class="mt-0.5 h-5 w-5 shrink-0 {{ $styles['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    @endif
    <div class="flex-1">{{ $slot }}</div>
</div>
