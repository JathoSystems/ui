<?php

use Illuminate\Database\Eloquent\Model;
use JathoSystems\Ui\Livewire\Forms\ModelForm;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Create/update form for a model, driven by its schema.
 *
 *   <livewire:ui::easy-form :model-class="Post::class" />                  create
 *   <livewire:ui::easy-form :model-class="Post::class" :model="$post" />   edit (implied because $post exists)
 *
 * The schema comes from the `schema` prop or the model's formSchema().
 *
 *   <livewire:ui::easy-form :model-class="Post::class" :model="$post">
 *       <div class="grid grid-cols-2 gap-4">
 *           <x-ui::input name="form.data.title" label="Title" wire:model="form.data.title" />
 *           <x-ui::input type="date" name="form.data.published_at" label="Published" wire:model="form.data.published_at" />
 *       </div>
 *       <livewire:slot name="actions">
 *           <x-ui::button type="submit" color="primary">Save</x-ui::button>
 *       </livewire:slot>
 *   </livewire:ui::easy-form>
 */
new class extends Component {
    use WithFileUploads;

    public ModelForm $form;

    /**
     * @param  class-string<Model>  $modelClass
     * @param  Model|null  $model
     * @param  array<string, string|array<string, mixed>>  $schema
     */
    public function mount(string $modelClass, mixed $model = null, array $schema = []): void
    {
        if ($model !== null && ! $model instanceof $modelClass) {
            throw new InvalidArgumentException(sprintf(
                'Expected an instance of [%s], got [%s].', $modelClass, get_debug_type($model),
            ));
        }

        $this->form->for($model ?? $modelClass, $schema);
    }

    public function save(): void
    {
        $this->form->save();
    }
};
?>

<div>
    <x-ui::form :fields="$form->fields" :editing="$form->isEditing()" submit="save">
        {{ $slot }}

        @if ($slots->has('actions'))
            <x-slot:actions>{{ $slots->get('actions') }}</x-slot:actions>
        @endif
    </x-ui::form>
</div>
