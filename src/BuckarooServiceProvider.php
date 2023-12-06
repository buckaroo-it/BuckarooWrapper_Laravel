<?php

namespace Buckaroo\Laravel;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

use Buckaroo\Laravel\Wrappers\BuckarooWrapper;
use Buckaroo\Laravel\Console\PublishCommand;
use Buckaroo\Laravel\Console\TransactionCommand;

class BuckarooServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();

        $this->loadRoutesFrom(__DIR__.'/../routes/buckaroo.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/buckaroo.php', 'buckaroo');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerApi();
        $this->registerManager();
        $this->registerCommands();
    }

    protected function registerApi()
    {
        $this->app->singleton('buckaroo.api', function (Container $app) {
            return new BuckarooWrapper($app['config']);
        });

        $this->app->alias('buckaroo.api', BuckarooWrapper::class);
    }

    protected function registerManager()
    {
        $this->app->singleton('buckaroo', function (Container $app) {
            return new BuckarooManager($app);
        });

        $this->app->alias('buckaroo', BuckarooManager::class);
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishCommand::class,
                TransactionCommand::class
            ]);
        }
    }

    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config' => $this->app->basePath('config'),
            ], ['buckaroo', 'buckaroo-config']);

            $this->publishes([
                __DIR__ . '/../database' => $this->app->basePath('database'),
            ], ['buckaroo', 'buckaroo-database']);

            $this->publishes([
                __DIR__ . '/Http' => $this->app->basePath('app/Http'),
            ], ['buckaroo', 'buckaroo-controllers']);
        }
    }

    public function provides()
    {
        return [
            'buckaroo'
        ];
    }
}
