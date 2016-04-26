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
        //
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
