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
        $this->app->singleton('GooglePlaces', function ($app) {
            $key = isset($app['config']['google.places.key'])
                ? $app['config']['google.places.key'] : null;

            return new PlacesApi($key);
        });
    }

    /**
     * Boot
     */
    public function boot()
    {
        $configFile = __DIR__ . '/../config/google.php';

        $this->publishes([
            $configFile => config_path('google.php'),
        ]);
    }
}
