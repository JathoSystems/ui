<?php

namespace JathoSystems\Ui;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class UiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ui');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ui');

        Blade::anonymousComponentPath(__DIR__.'/../resources/views/components', 'ui');

        Livewire::addNamespace('ui', viewPath: __DIR__.'/../resources/views/livewire');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/ui'),
        ], 'ui-views');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/ui'),
        ], 'ui-lang');
    }
}
