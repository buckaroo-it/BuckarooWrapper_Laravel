<?php

namespace Buckaroo\Laravel;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

use Buckaroo\BuckarooClient;
use Buckaroo\Laravel\Wrappers\BuckarooApi;

class BuckarooServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
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
        $this->app->singleton('buckaroo', function (Container $app) {
            return new BuckarooWrapper($app);
        });

        $this->app->alias('buckaroo', BuckarooClient::class);
    }

    protected function registerRoutes()
    {
        $this->app->singleton('buckaroo.client', function (Container $app) {
            return new BuckarooApi();
        });
    }
}
