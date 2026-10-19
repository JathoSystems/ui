@props(['trigger', 'triggerClass' => ''])

<div>
    <div
        x-data="{
            open: false,
            dropdownTop: '0px',
            dropdownLeft: 'auto',
            dropdownRight: 'auto',
            _onScrollOrResize: null,
            init() {
                this.$watch('open', () => this.syncTriggerState());
                this.$nextTick(() => this.syncTriggerState());
                this._onScrollOrResize = () => { if (this.open) this.updatePosition(); };
                window.addEventListener('scroll', this._onScrollOrResize, true);
                window.addEventListener('resize', this._onScrollOrResize);
            },
            destroy() {
                window.removeEventListener('scroll', this._onScrollOrResize, true);
                window.removeEventListener('resize', this._onScrollOrResize);
            },
            updatePosition() {
                const rect = this.$refs.trigger.getBoundingClientRect();
                const dropdownEl = this.$refs.dropdown;
                const dropdownHeight = dropdownEl ? dropdownEl.offsetHeight : 200;
                const dropdownWidth = dropdownEl ? dropdownEl.offsetWidth : 192;
                const spaceBelow = window.innerHeight - rect.bottom;
                const spaceAbove = rect.top;

                if (spaceBelow < dropdownHeight && spaceAbove > spaceBelow) {
                    this.dropdownTop = (rect.top - dropdownHeight) + 'px';
                } else {
                    this.dropdownTop = rect.bottom + 'px';
                }

                if (rect.left + dropdownWidth > window.innerWidth) {
                    this.dropdownLeft = 'auto';
                    this.dropdownRight = (window.innerWidth - rect.right) + 'px';
                } else {
                    this.dropdownLeft = rect.left + 'px';
                    this.dropdownRight = 'auto';
                }
            },
            triggerControl() {
                return this.$refs.trigger.querySelector('button, a[href], [tabindex]');
            },
            syncTriggerState() {
                const control = this.triggerControl();
                if (! control) return;
                control.setAttribute('aria-haspopup', 'true');
                control.setAttribute('aria-expanded', this.open ? 'true' : 'false');
            },
            openDropdown() {
                this.open = true;
                this.$nextTick(() => this.updatePosition());
            },
            closeDropdown(returnFocus = false) {
                this.open = false;
                if (returnFocus) this.triggerControl()?.focus();
            }
        }"
        @click.outside="closeDropdown()"
        @keydown.escape="if (open) { $event.stopPropagation(); closeDropdown(true); }"
        @focusout="if (open && $event.relatedTarget && ! $el.contains($event.relatedTarget)) closeDropdown()"
        @modal:opened.window="closeDropdown()"
    >
        <div x-ref="trigger" @click="open ? closeDropdown() : openDropdown()" class="cursor-pointer {{ $triggerClass }}">
            {{ $trigger }}
        </div>

        <div
            x-show="open"
            x-ref="dropdown"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            :style="{ position: 'fixed', top: dropdownTop, left: dropdownLeft, right: dropdownRight, zIndex: 200 }"
            {{ $attributes->merge(['class' => 'p-2 bg-white dark:bg-zinc-800 rounded-md shadow-xl border']) }}
            style="display: none;"
            @click.stop
        >
            {{ $slot }}
        </div>
    </div>
</div>
