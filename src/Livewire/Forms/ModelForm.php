<?php

namespace JathoSystems\Ui\Livewire\Forms;

use BackedEnum;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Livewire\Attributes\Locked;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

/**
 * Generic create/update form for an Eloquent model.
 *
 * Edit mode is derived from the model passed to for(): an existing model is updated,
 * a class string or a new instance is created. The schema falls back to a formSchema()
 * method on the model; rules come from the schema and/or a formRules() method on the model.
 */
class ModelForm extends Form
{
    /** @var class-string<Model>|null */
    #[Locked]
    public ?string $modelClass = null;

    #[Locked]
    public int|string|null $modelId = null;

    /** @var array<string, array<string, mixed>> */
    #[Locked]
    public array $fields = [];

    /** @var array<string, mixed> */
    public array $data = [];

    /**
     * @param  Model|class-string<Model>  $model
     * @param  array<string, string|array<string, mixed>>  $schema
     */
    public function for(Model|string $model, array $schema = []): static
    {
        if (is_string($model)) {
            if (! is_subclass_of($model, Model::class)) {
                throw new InvalidArgumentException("[{$model}] is not an Eloquent model.");
            }

            $model = new $model;
        }

        if ($schema === [] && method_exists($model, 'formSchema')) {
            $schema = $model->formSchema();
        }

        $this->modelClass = $model::class;
        $this->modelId = $model->exists ? $model->getKey() : null;
        $this->fields = FormSchema::normalize($schema);
        $this->data = $this->initialData($model);

        return $this;
    }

    public function isEditing(): bool
    {
        return $this->modelId !== null;
    }

    public function model(): Model
    {
        $modelClass = $this->modelClass ?? throw new InvalidArgumentException('Call for() before using the form.');

        return $this->isEditing() ? $modelClass::findOrFail($this->modelId) : new $modelClass;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [];

        $model = $this->modelClass ? new $this->modelClass : null;
        if ($model && method_exists($model, 'formRules')) {
            foreach ($model->formRules() as $name => $fieldRules) {
                $rules["data.{$name}"] = $fieldRules;
            }
        }

        foreach ($this->fields as $name => $field) {
            if ($field['rules'] !== null) {
                $rules["data.{$name}"] = $field['rules'];
            }
        }

        return array_map(fn (mixed $fieldRules): mixed => $this->replaceIdPlaceholder($fieldRules), $rules);
    }

    /**
     * @return array<string, string>
     */
    public function validationAttributes(): array
    {
        $attributes = [];

        foreach ($this->fields as $name => $field) {
            $attributes["data.{$name}"] = Str::lower($field['label']);
        }

        return $attributes;
    }

    public function save(): Model
    {
        $this->validate();

        $model = $this->model();
        $attributes = [];
        $replacedFiles = [];

        foreach ($this->savableFields() as $name) {
            $value = $this->data[$name] ?? null;

            if (! $model->isFillable($name)) {
                continue;
            }

            if ($this->isFileField($name)) {
                if (! $value instanceof TemporaryUploadedFile) {
                    continue;
                }

                $disk = $this->fields[$name]['disk'] ?? 'public';
                $directory = $this->fields[$name]['directory'] ?? Str::kebab(Str::plural(class_basename($model)));

                if ($previousPath = $model->getOriginal($name)) {
                    $replacedFiles[] = [$disk, $previousPath];
                }

                $value = $value->store($directory, $disk);
            }

            $attributes[$name] = $value === '' ? null : $value;
        }

        $wasEditing = $this->isEditing();
        $model->fill($attributes)->save();

        foreach ($replacedFiles as [$disk, $path]) {
            Storage::disk($disk)->delete($path);
        }

        $eventName = Str::kebab(class_basename($model)).($wasEditing ? '-updated' : '-created');
        $this->getComponent()->dispatch($eventName, id: $model->getKey());
        $this->getComponent()->dispatch('close-modal');

        if ($wasEditing) {
            $this->data = $this->initialData($model->refresh());
        } else {
            $this->data = $this->initialData(new $model);
            $this->resetErrorBag();
        }

        return $model;
    }

    /**
     * @return array<string, mixed>
     */
    protected function initialData(Model $model): array
    {
        $names = $this->fields !== [] ? array_keys($this->fields) : $model->getFillable();
        $data = [];

        foreach ($names as $name) {
            $field = $this->fields[$name] ?? null;

            if ($field && $field['type'] === 'file') {
                $data[$name] = null;

                continue;
            }

            $value = $model->exists ? $model->getAttribute($name) : ($field['default'] ?? null);
            $data[$name] = $this->toFormValue($value, $field['type'] ?? null);
        }

        return $data;
    }

    protected function toFormValue(mixed $value, ?string $type): mixed
    {
        return match (true) {
            $value instanceof DateTimeInterface => $value->format($type === 'datetime-local' ? 'Y-m-d\TH:i' : 'Y-m-d'),
            $value instanceof BackedEnum => $value->value,
            default => $value,
        };
    }

    /**
     * Only validated fields are written, so a client adding extra keys to $data can't fill unvalidated attributes.
     *
     * @return list<string>
     */
    protected function savableFields(): array
    {
        if ($this->fields !== []) {
            return array_keys($this->fields);
        }

        return array_map(fn (string $key): string => Str::after($key, 'data.'), array_keys($this->rules()));
    }

    protected function isFileField(string $name): bool
    {
        return ($this->fields[$name]['type'] ?? null) === 'file'
            || ($this->data[$name] ?? null) instanceof TemporaryUploadedFile;
    }

    protected function replaceIdPlaceholder(mixed $rules): mixed
    {
        $id = $this->modelId === null ? 'NULL' : (string) $this->modelId;

        if (is_string($rules)) {
            return str_replace('{id}', $id, $rules);
        }

        if (is_array($rules)) {
            return array_map(fn (mixed $rule): mixed => is_string($rule) ? str_replace('{id}', $id, $rule) : $rule, $rules);
        }

        return $rules;
    }
}
