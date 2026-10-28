# Plan: `easy-form` Livewire component

## Goal

A one-liner form that creates or updates an Eloquent model from its schema:

```blade
{{-- create --}}
<livewire:ui::easy-form :model-class="App\Models\Post::class" />

{{-- edit (implied by passing a model) --}}
<livewire:ui::easy-form :model-class="App\Models\Post::class" :model="$post" />

{{-- optional explicit schema, otherwise Post::formSchema() is used --}}
<livewire:ui::easy-form :model-class="App\Models\Post::class" :schema="['title' => 'text']" />
```

## What already exists (reuse, don't rewrite)

- `src/Livewire/Forms/FormSchema.php`: normalizes short/long schemas.
- `src/Livewire/Forms/ModelForm.php`: Livewire `Form` object with `for()`, `rules()`, `save()`, file uploads,
  `{id}` rule placeholders, `*-created`/`*-updated` events. State (`modelClass`, `modelId`, `fields`) is `#[Locked]`.
- `resources/views/components/form.blade.php`: renders fields from a normalized schema.
- `resources/views/livewire/model-form.blade.php`: single-file Livewire component, `mount(Model|string $model, array $schema)`.

`easy-form` is basically `model-form` with the `modelClass` + `model` props and a hardened save path.

## Steps

1. **Component** `resources/views/livewire/easy-form.blade.php` (single-file, same style as `model-form`)
   - `mount(string $modelClass, ?Model $model = null, array $schema = [])`
   - Throw if `$modelClass` isn't a `Model` subclass, or if `$model` is given and isn't an instance of `$modelClass`.
   - Edit mode = `$model?->exists`. Call `$this->form->for($model ?? $modelClass, $schema)`.
   - Render `<x-ui::form :fields="$form->fields" :editing="$form->isEditing()" submit="save" />`.
   - Optionally make `model-form` delegate to the same logic later; out of scope for v1.

2. **Mass-assignment guard** (the `is_admin=true` check), in `ModelForm::save()` / `savableFields()`:
   - Writable = schema fields ∩ `$model->getFillable()` (explicit `$fillable`, **not** `isFillable()`, which is
     true for everything when the model uses `$guarded = []` or `Model::unguard()`).
   - Always strip a hard deny-list regardless of schema: primary key, `created_at`/`updated_at`/`deleted_at`,
     and anything in `$model->formGuarded()` if the model defines it. Ship a small default list
     (`is_admin`, `role`, `roles`, `permissions`, `remember_token`, `email_verified_at`) and let the model override it.
   - If a schema field gets dropped by this guard, throw in `for()` (dev error, fail loud) instead of silently skipping.
   - Never trust `$data` keys: only iterate the locked `$fields`, never `array_keys($this->data)`. (Already the case;
     add a test so it stays that way.)
   - `hidden` fields: their value comes from the client, so keep the value server-side. Store hidden defaults in a
     `#[Locked] array $fixed` and merge them in `save()` instead of reading them from `$data`.

3. **Authorization**: before saving, if a policy exists for the model, run `Gate::authorize('create', $modelClass)`
   or `Gate::authorize('update', $model)`. Skip silently when no policy is registered (opt-in via
   `Gate::getPolicyFor()`).

4. **Tests** (needs `orchestra/testbench` + `pestphp/pest` as dev deps; none exist yet):
   - create mode saves, edit mode updates the existing row.
   - `$set('form.data.is_admin', true)` then `save` → `is_admin` stays false, even with `$guarded = []`.
   - tampering with a hidden field value has no effect.
   - tampering with locked `modelId`/`modelClass` throws.
   - schema with a deny-listed field throws in `mount`.
   - policy denies → 403.

5. **Docs**: short usage section in a README (none exists yet), including `formSchema()`, `formRules()`, `formGuarded()`.

## Out of scope

Relations / many-to-many fields, multi-step forms, merging `model-form` into `easy-form`.

---

## Handoff prompt

> In the `jathosystems/ui` repo (Laravel 12 / Livewire 4 package), implement the plan in `docs/plans/easy-form.md`.
> Add a single-file Livewire component `resources/views/livewire/easy-form.blade.php` (usage
> `<livewire:ui::easy-form :model-class="Post::class" :model="$post" />`; edit mode implied when `$model` exists),
> built on the existing `ModelForm` form object and `<x-ui::form>` component — follow the style of
> `resources/views/livewire/model-form.blade.php`. Harden `ModelForm::save()` so a client cannot set attributes
> outside the schema or sensitive attributes like `is_admin`: only write schema fields that are in the model's
> explicit `getFillable()` (not `isFillable()`), apply a deny-list (primary key, timestamps, `is_admin`, `role(s)`,
> `permissions`, `remember_token`, `email_verified_at`, plus an optional `formGuarded()` on the model), throw in
> `for()` when the schema contains a denied field, and keep `hidden` field values server-side in a locked property.
> Add a policy check (`create`/`update`) when a policy is registered. Set up Pest + Testbench and cover the tests
> listed in the plan, especially the `is_admin` tampering case with `$guarded = []`. Keep changes small, commit,
> and push to the current feature branch.
