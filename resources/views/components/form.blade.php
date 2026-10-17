@props([
    'schema' => null,
    'fields' => null,
    'form' => 'form',
    'submit' => 'save',
    'editing' => null,
    'cancel' => true,
])

@php
    $fields ??= $schema !== null ? \JathoSystems\Ui\Livewire\Forms\FormSchema::normalize($schema) : [];
    $submitLabel = match ($editing) {
        true => __('ui::ui.update'),
        false => __('ui::ui.create'),
        default => __('ui::ui.save'),
    };
@endphp

<form wire:submit="{{ $submit }}" {{ $attributes }}>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        @foreach ($fields as $name => $field)
            @php
                $model = "{$form}.data.{$name}";
                $rules = is_array($field['rules']) ? $field['rules'] : explode('|', (string) $field['rules']);
                $extraAttributes = new \Illuminate\View\ComponentAttributeBag(array_merge(
                    in_array('required', $rules, true) && $field['type'] !== 'file' ? ['required' => true] : [],
                    $field['attributes'],
                ));
            @endphp

            @switch($field['type'])
                @case('textarea')
                    <x-ui::textarea :name="$model" :label="$field['label']" :help="$field['help']" :placeholder="$field['placeholder']"
                        wire:model="{{ $model }}" wire:key="field-{{ $name }}" :attributes="$extraAttributes" />
                    @break

                @case('select')
                    <x-ui::select :name="$model" :label="$field['label']" :help="$field['help']" :options="$field['options']"
                        :placeholder="$field['placeholder']" wire:model="{{ $model }}" wire:key="field-{{ $name }}" :attributes="$extraAttributes" />
                    @break

                @case('searchable-select')
                    <x-ui::searchable-select :name="$model" :label="$field['label']" :help="$field['help']" :options="$field['options']"
                        :placeholder="$field['placeholder'] ?? __('ui::ui.search')" wire:model="{{ $model }}" :attributes="$extraAttributes" />
                    @break

                @case('checkbox')
                    <x-ui::checkbox :name="$model" :label="$field['label']" :help="$field['help']"
                        wire:model="{{ $model }}" wire:key="field-{{ $name }}" :attributes="$extraAttributes" />
                    @break

                @case('hidden')
                    <input type="hidden" wire:model="{{ $model }}" wire:key="field-{{ $name }}">
                    @break

                @default
                    <x-ui::input :type="$field['type']" :name="$model" :label="$field['label']" :help="$field['help']"
                        :placeholder="$field['placeholder']" wire:model="{{ $model }}" wire:key="field-{{ $name }}" :attributes="$extraAttributes" />
            @endswitch
        @endforeach
    @endif

    <div class="flex flex-row gap-2 justify-end mt-4">
        @isset($actions)
            {{ $actions }}
        @else
            @if ($cancel)
                <x-ui::button color="neutral" @click="$dispatch('close-modal')">
                    {{ __('ui::ui.cancel') }}
                </x-ui::button>
            @endif
            <x-ui::button type="submit" color="primary" wire:loading.attr="disabled">
                @if ($editing)
                    <x-lucide-save class="size-5" />
                @else
                    <x-lucide-plus class="size-5" />
                @endif
                {{ $submitLabel }}
            </x-ui::button>
        @endisset
    </div>
</form>
