@props(['active' => false])

@php
    $classes = $active
        ? 'bg-white/15 text-white shadow-sm ring-1 ring-white/20'
        : 'text-indigo-100/70 hover:bg-white/10 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 '.$classes]) }}>
    @isset($icon)
        <span @class([
            'flex h-5 w-5 shrink-0 items-center justify-center transition-colors',
            'text-white' => $active,
            'text-indigo-300/60 group-hover:text-white' => ! $active,
        ])>
            {{ $icon }}
        </span>
    @endisset
    <span x-show="!sidebarCollapsed" x-cloak class="truncate">{{ $slot }}</span>
    @if ($active)
        <span x-show="!sidebarCollapsed" x-cloak class="ml-auto h-1.5 w-1.5 shrink-0 rounded-full bg-indigo-300"></span>
    @endif
</a>
