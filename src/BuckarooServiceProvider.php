<?php

namespace Buckaroo\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;

use Buckaroo\Laravel\BuckarooApi;

class BuckarooServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBuckarooApi();
        $this->registerMigrations();
        $this->registerRoutes();
    }

    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/buckaroo.php');

        if($this->app instanceof LaravelApplication && $this->app->runningInConsole())
        {
            $this->publishes([$source => config_path('buckaroo.php')]);
        }

        $this->mergeConfigFrom($source, 'buckaroo');
    }

    protected function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/buckaroo.php');
    }

    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function registerBuckarooApi()
    {
        $this->app->singleton('buckaroo.client', function (Container $app) {
            return new BuckarooApi();
        });
    }
}
