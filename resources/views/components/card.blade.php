@props(['title' => null, 'subtitle' => null, 'innerClass' => ''])

<div {{ $attributes->class('flex flex-col items-start justify-between border divide-y rounded-lg divide-gray-200 dark:divide-gray-600 bg-gray-100 dark:bg-zinc-900') }}>
    @if ($title || isset($header))
        <div class="flex items-center p-2 px-3 w-full">
            @if ($title)
                <h2 class="text-xl font-semibold">{{ $title }}</h2>
            @endif
            @if ($subtitle)
                <p class="ml-2 text-gray-600 dark:text-gray-400">{{ $subtitle }}</p>
            @endif
            {{ $header ?? '' }}
        </div>
    @endif
    <div @class(['flex-1 p-2 px-3 w-full', $innerClass])>
        {{ $slot }}
    </div>
    @isset($actions)
        <div class="flex items-center gap-2 p-2 px-3 w-full justify-end">
            {{ $actions }}
        </div>
    @endisset
</div>
