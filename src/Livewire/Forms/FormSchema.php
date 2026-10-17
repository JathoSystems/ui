<?php

namespace JathoSystems\Ui\Livewire\Forms;

use Illuminate\Support\Str;
use InvalidArgumentException;

class FormSchema
{
    /**
     * @var list<string>
     */
    public const TYPES = [
        'text', 'email', 'password', 'number', 'date', 'datetime-local', 'time', 'url', 'tel', 'color',
        'textarea', 'select', 'searchable-select', 'checkbox', 'file', 'hidden',
    ];

    /**
     * Normalize a short or long form schema into a list of field definitions.
     *
     * Short: ['name' => 'text']
     * Long:  ['country_id' => ['type' => 'select', 'label' => 'Land', 'options' => [...], 'rules' => 'nullable|exists:countries,id']]
     *
     * @param  array<string, string|array<string, mixed>>  $schema
     * @return array<string, array{name: string, type: string, label: string, options: array<array-key, string>, placeholder: ?string, rules: string|array<int, mixed>|null, default: mixed, help: ?string, disk: string, directory: ?string, attributes: array<string, mixed>}>
     */
    public static function normalize(array $schema): array
    {
        $fields = [];

        foreach ($schema as $name => $definition) {
            if (! is_string($name)) {
                throw new InvalidArgumentException('Form schema keys must be field names.');
            }

            $definition = is_string($definition) ? ['type' => $definition] : $definition;
            $type = $definition['type'] ?? 'text';

            if (! in_array($type, self::TYPES, true)) {
                throw new InvalidArgumentException("Unsupported form field type [{$type}] for [{$name}].");
            }

            $fields[$name] = [
                'name' => $name,
                'type' => $type,
                'label' => $definition['label'] ?? Str::headline(Str::beforeLast($name, '_id')),
                'options' => $definition['options'] ?? [],
                'placeholder' => $definition['placeholder'] ?? null,
                'rules' => $definition['rules'] ?? null,
                'default' => $definition['default'] ?? ($type === 'checkbox' ? false : null),
                'help' => $definition['help'] ?? null,
                'disk' => $definition['disk'] ?? 'public',
                'directory' => $definition['directory'] ?? null,
                'attributes' => $definition['attributes'] ?? [],
            ];
        }

        return $fields;
    }
}
