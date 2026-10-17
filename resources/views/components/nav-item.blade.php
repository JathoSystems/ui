@props(['href', 'current' => false])

<a href="{{ $href }}" @if ($current) aria-current="page" @endif
    {{ $attributes->class([
        'flex items-center gap-2 px-2 py-1.5 rounded-md transition-colors duration-200 text-sm text-black dark:text-white hover:bg-gray-100 dark:hover:bg-zinc-800',
        'bg-gray-100 dark:bg-zinc-800' => $current,
    ]) }}>
    {{ $slot }}
</a>
