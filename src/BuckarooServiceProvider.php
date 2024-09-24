<?php

namespace Buckaroo\Laravel;

use Buckaroo\Laravel\Console\PublishCommand;
use Buckaroo\Laravel\Wrappers\BuckarooClient;
use Buckaroo\Laravel\Wrappers\BuckarooManager;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

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

        if (config('buckaroo.routes.load')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/buckaroo.php');
        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../config/buckaroo.php', 'buckaroo');
    }

    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__ . '/../config' => $this->app->basePath('config')],
                ['buckaroo', 'buckaroo-config']
            );

            $this->publishes(
                [__DIR__ . '/../routes' => $this->app->basePath('routes')],
                ['buckaroo', 'buckaroo-routes']
            );

            $this->publishes(
                [__DIR__ . '/../database' => $this->app->basePath('database')],
                ['buckaroo', 'buckaroo-database']
            );
        }
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
        $this->app->singleton('buckaroo.api', fn (Container $app) => new BuckarooClient($app['config']));

        $this->app->alias('buckaroo.api', BuckarooClient::class);
    }

    protected function registerManager()
    {
        $this->app->singleton('buckaroo', fn (Container $app) => new BuckarooManager($app));

        $this->app->alias('buckaroo', BuckarooManager::class);
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishCommand::class,
            ]);
        }
    }

    public function provides()
    {
        return [
            'buckaroo',
        ];
    }
}
