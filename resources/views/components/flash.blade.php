@props([
    'dismissible' => true,
])

@foreach (['success', 'error', 'warning', 'info'] as $variant)
    @if ($message = session($variant))
        <x-ui::alert :variant="$variant" :dismissible="$dismissible" {{ $attributes->class('mb-2') }}>
            {{ $message }}
        </x-ui::alert>
    @endif
@endforeach
