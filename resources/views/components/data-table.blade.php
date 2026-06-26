@props([
    'title' => null,
    'searchPlaceholder' => 'Cari...',
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800']) }}>
    <div class="flex flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
        @if ($title)
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            @isset($search)
                {{ $search }}
            @else
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="search"
                        placeholder="{{ $searchPlaceholder }}"
                        class="w-full rounded-lg border-gray-300 bg-gray-50 py-2 pl-9 pr-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 sm:w-64"
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
        <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-700">
            {{ $pagination }}
        </div>
    @endisset
</div>
