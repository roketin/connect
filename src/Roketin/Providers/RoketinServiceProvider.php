<?php

namespace Roketin\Providers;

use Illuminate\Support\ServiceProvider;

class RoketinServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = realpath(__DIR__ . '/../../../config/config.php');
        $this->publishes([$path => config_path('roketin.php')], 'config');
        $this->mergeConfigFrom($path, 'roketin');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('roketin', function () {
            return new \App\Roketin\Roketin();
        });
    }

}
