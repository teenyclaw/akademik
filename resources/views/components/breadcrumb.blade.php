@props(['items' => []])

<nav aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400">
        <li>
            <a href="{{ route('dashboard') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
            </a>
        </li>
        @foreach ($items as $item)
            <li class="flex items-center gap-1.5">
                <svg class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
                @if (! empty($item['url']))
                    <a href="{{ $item['url'] }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
