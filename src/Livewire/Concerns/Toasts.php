<?php

namespace JathoSystems\Ui\Livewire\Concerns;

trait Toasts
{
    /**
     * @param  'success'|'error'|'warning'|'info'  $variant
     */
    public function toast(string $message, string $variant = 'success', ?int $duration = null): void
    {
        $this->dispatch('toast', ...array_filter([
            'message' => $message,
            'variant' => $variant,
            'duration' => $duration,
        ], fn (mixed $value): bool => $value !== null));
    }

    /**
     * Shows a toast after a redirect (the next full page load).
     *
     * @param  'success'|'error'|'warning'|'info'  $variant
     */
    public function toastAfterRedirect(string $message, string $variant = 'success'): void
    {
        session()->flash('toast', ['message' => $message, 'variant' => $variant]);
    }
}
