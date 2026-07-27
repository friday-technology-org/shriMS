<?php

namespace MyPlugin\Demo;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class DemoPluginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Load views (publishable)
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'demoplugin');
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/demoplugin'),
        ], 'views');

        // Publish public assets
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/demoplugin'),
        ], 'public');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind a facade if desired
        $this->app->singleton('demoplugin', function () {
            return new \MyPlugin\Demo\Facades\DemoPlugin();
        });
    }
}
