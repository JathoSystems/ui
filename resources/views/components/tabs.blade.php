@props([
    'default' => null,
])

{{--
    Usage example:
    <x-ui::tabs default="editions">
        <x-slot:list>
            <x-ui::tabs.tab name="editions">Edities</x-ui::tabs.tab>
            <x-ui::tabs.tab name="stages">Etappes</x-ui::tabs.tab>
        </x-slot:list>

        <x-ui::tabs.panel name="editions">...</x-ui::tabs.panel>
        <x-ui::tabs.panel name="stages">...</x-ui::tabs.panel>
    </x-ui::tabs>
--}}
<div
    x-data="{
        active: @js($default),
        init() {
            this.active ??= this.$root.querySelector('[role=tab]')?.dataset.tab ?? null;
        },
        move(step) {
            const tabs = Array.from(this.$root.querySelectorAll('[role=tab]'));
            const next = tabs[(tabs.findIndex(t => t.dataset.tab === this.active) + step + tabs.length) % tabs.length];
            this.active = next.dataset.tab;
            next.focus();
        },
    }"
    x-modelable="active"
    {{ $attributes }}
>
    <div role="tablist" class="flex flex-row flex-wrap gap-1 border-b mb-4"
        @keydown.arrow-right.prevent="move(1)"
        @keydown.arrow-left.prevent="move(-1)">
        {{ $list ?? '' }}
    </div>

    {{ $slot }}
</div>
