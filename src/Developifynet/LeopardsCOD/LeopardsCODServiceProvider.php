<?php

namespace Developifynet\LeopardsCOD;

use Illuminate\Support\ServiceProvider;

class LeopardsCODServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('leopardscod', function () {
            return $this->app->make('Developifynet\LeopardsCOD\LeopardsCODClient');
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

    }

}
