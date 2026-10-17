<?php

use Illuminate\Database\Eloquent\Model;
use JathoSystems\Ui\Livewire\Forms\ModelForm;
use Livewire\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public ModelForm $form;

    /**
     * @param  Model|class-string<Model>  $model
     * @param  array<string, string|array<string, mixed>>  $schema
     */
    public function mount(Model|string $model, array $schema = []): void
    {
        $this->form->for($model, $schema);
    }

    public function save(): void
    {
        $this->form->save();
    }
};
?>

<div>
    <x-ui::form :fields="$form->fields" :editing="$form->isEditing()" submit="save" />
</div>
