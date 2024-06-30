<?php

namespace SKAgarwal\GoogleApi;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $configFile = __DIR__ . '/../config/google.php';

        $this->publishes([
            $configFile => config_path('google.php'),
        ]);
    }
}
