@props(['name'])

<button
    type="button"
    role="tab"
    data-tab="{{ $name }}"
    @click="active = @js($name)"
    :aria-selected="active === @js($name) ? 'true' : 'false'"
    :tabindex="active === @js($name) ? 0 : -1"
    :class="active === @js($name)
        ? 'border-blue-500 text-blue-600 dark:text-blue-400'
        : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white'"
    {{ $attributes->class('-mb-px flex items-center gap-2 border-b-2 px-3 py-2 text-sm font-medium cursor-pointer transition-colors') }}
>
    {{ $slot }}
</button>
