@props(['heading'])
<div {{ $attributes->class(['flex flex-col gap-2']) }}>
    <div class="text-sm mt-2 text-gray-700 dark:text-gray-300">
        {{ $heading }}
    </div>

    {{ $slot }}
</div>
