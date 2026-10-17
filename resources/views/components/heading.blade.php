@props(['title' => ''])

<div {{ $attributes->class('flex flex-1 justify-center sticky top-0 z-5 pt-4 pb-2 -translate-y-4 -mb-2 mt-6 md:mt-0 bg-white dark:bg-zinc-800 border-b border-neutral-200 dark:border-b-gray-600') }}>
    <div class="flex gap-1 flex-row justify-between items-center flex-1 max-w-6xl">
        <div class="flex justify-center flex-col gap-2 flex-1">
            <h1 class="text-lg font-bold text-black dark:text-white">
                {{ $title }}
            </h1>
            {{ $slot }}
        </div>
        @isset($actions)
            <div class="flex items-center justify-end gap-2 flex-wrap flex-1">
                {{ $actions }}
            </div>
        @endisset
    </div>
</div>
