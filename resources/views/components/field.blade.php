@props([
    'name' => null,
    'label' => null,
    'help' => null,
    'required' => false,
    'inline' => false,
    'wrapsControl' => true,
])

<div {{ $attributes->class('mb-2') }}>
    <{{ $wrapsControl ? 'label' : 'div' }} @class(['flex flex-col', 'flex-row-reverse justify-end items-center gap-2' => $inline])>
        @if ($label)
            <span class="text-black dark:text-white">
                {{ $label }}
                @if ($required)
                    <span class="text-red-500">*</span>
                @endif
            </span>
        @endif

        {{ $slot }}
    </{{ $wrapsControl ? 'label' : 'div' }}>

    @if ($help)
        <p class="text-sm mt-1 flex flex-row gap-1 items-center">
            <x-lucide-info class="size-4 shrink-0" />
            {{ $help }}
        </p>
    @endif

    @if ($name)
        @error($name)
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    @endif
</div>
