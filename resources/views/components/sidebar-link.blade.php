@props(['active' => false])

@php
    $classes = $active
        ? 'bg-indigo-600 text-white shadow-sm'
        : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700/60';
@endphp

<a {{ $attributes->merge(['class' => 'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition '.$classes]) }}>
    @isset($icon)
        <span @class([
            'flex h-5 w-5 shrink-0 items-center justify-center',
            'text-white' => $active,
            'text-gray-400 group-hover:text-indigo-600 dark:text-gray-500 dark:group-hover:text-indigo-400' => ! $active,
        ])>
            {{ $icon }}
        </span>
    @endisset
    <span x-show="!sidebarCollapsed" x-cloak class="truncate">{{ $slot }}</span>
</a>
