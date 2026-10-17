@aware(['default' => null])

@props(['name'])

<div
    role="tabpanel"
    x-show="active === @js($name)"
    @if ($default !== null && $default !== $name) style="display: none;" @endif
    {{ $attributes }}
>
    {{ $slot }}
</div>
