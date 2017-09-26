<?php

namespace AloPeyk\Api\RESTful\Provider;

use AloPeyk\Api\RESTful\ApiHandler;
use Illuminate\Support\ServiceProvider;

class AloPeykServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../Config/alopeyk.php' => config_path('alopeyk.php')], 'alopeyk');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('AloPeykApiHandler', function () {
            return new ApiHandler();
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/alopeyk.php', 'alopeyk'
        );
    }
}
