<?php

namespace Buckaroo\Laravel;

use Illuminate\Support\ServiceProvider;
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
        $this->registerMigrations();
        $this->registerRoutes();
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
        $this->app->bind('buckaroo', function ($app) {
            return new BuckarooApi();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBuckarooApi();
    }
}
