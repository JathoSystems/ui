# jathosystems/ui

A shared kit of Blade and Livewire 4 UI components. Components are anonymous Blade components in the `ui` namespace, so you use them as `<x-ui::button>`, `<x-ui::modal>` and so on.

## Installation

```bash
composer require jathosystems/ui
```

The service provider is auto-discovered. To let Tailwind v4 see the package's classes, add a source and import the shared CSS in your app stylesheet:

```css
@import 'tailwindcss';
@import '../../vendor/jathosystems/ui/resources/css/ui.css';

@source '../../vendor/jathosystems/ui/resources/views';
```

## Styling principle

Components expose **presets** (for example `columns="3"`, `gap="md"`, `aspect="16:9"`) rather than accepting free-form classes for their layout. Each preset maps to a fixed, literal set of Tailwind classes inside the component. Updating a component therefore updates every place it is used, consistently.

## Media and collection components

| Component | Purpose | Key props |
|---|---|---|
| `x-ui::media` | A single image, video or YouTube embed (lite: the iframe only loads on click) | `type` (`image`/`video`/`youtube`), `src`, `youtube`, `poster`, `alt`, `width`, `height`, `aspect` (`auto`/`square`/`4:3`/`3:4`/`16:9`/`9:16`/`21:9`), `fit` (`cover`/`contain`), `autoplay`, `controls` |
| `x-ui::carousel` + `x-ui::carousel.slide` | Scroll-snap carousel with arrows, keyboard, dots or thumbnail navigation, and autoplay | `per-view` (1/2/3), `gap` (`none`/`sm`/`md`/`lg`), `nav` (`dots`/`thumbnails`/`none`), `autoplay` (ms); slide: `thumbnail` |
| `x-ui::masonry` + `x-ui::masonry.item` | CSS-columns masonry | `columns` (2/3/4), `gap` |
| `x-ui::media-grid` | Responsive grid | `columns` (2/3/4), `gap` |

```blade
<x-ui::carousel nav="thumbnails" :autoplay="5000">
    @foreach ($items as $item)
        <x-ui::carousel.slide :thumbnail="$item->url">
            <x-ui::media :src="$item->url" aspect="16:9" />
        </x-ui::carousel.slide>
    @endforeach
</x-ui::carousel>

<x-ui::masonry columns="3" gap="md">
    @foreach ($items as $item)
        <x-ui::masonry.item>
            <x-ui::media :src="$item->url" :width="$item->width" :height="$item->height" />
        </x-ui::masonry.item>
    @endforeach
</x-ui::masonry>
```

Autoplay pauses on hover, on focus, when the tab is hidden, and when the user prefers reduced motion.

## Editor helpers

| Component | Purpose |
|---|---|
| `x-ui::segmented` | Button-group picker for preset values: `<x-ui::segmented wire:model.live="settings.gap" :options="['sm' => 'S', 'md' => 'M', 'lg' => 'L']" />` |
| `x-ui::dropzone` | Drag-and-drop file picker for a `WithFileUploads` property, with progress: `<x-ui::dropzone wire:model="uploads" multiple accept="image/*,video/*" />` |
| `x-ui::sortable` + `x-ui::sortable.item` + `x-ui::sortable.handle` | Drag-to-reorder list on top of Livewire 4's `wire:sort` |

```blade
<x-ui::sortable handler="moveItem">
    @foreach ($items as $item)
        <x-ui::sortable.item :item="$item->id">
            <x-ui::sortable.handle /> {{ $item->name }}
        </x-ui::sortable.item>
    @endforeach
</x-ui::sortable>
```

```php
public function moveItem(int $id, int $position): void { /* ... */ }
```

## Other components

alert, animated-text, badge, breadcrumb, button, card, checkbox, confirm, content, dropdown, empty-state, field, flash, form, heading, input, loading, modal, nav-group, nav-item, pagination, searchable-select, select, shelf, table (+ cell, heading, row), tabs (+ tab, panel), textarea, toasts, toggle.

The Livewire `ModelForm` (`JathoSystems\Ui\Livewire\Forms\ModelForm`) together with `<x-ui::form :schema="...">` builds create/edit forms from a schema. The `Toasts` concern (`JathoSystems\Ui\Livewire\Concerns\Toasts`) sends toasts to `<x-ui::toasts />`.
