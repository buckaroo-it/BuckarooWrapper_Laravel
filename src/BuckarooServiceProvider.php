<?php

namespace App\Buckaroo;

use App\Buckaroo\Wrappers\BuckarooWrapper;
use Illuminate\Contracts\Container\Container;
use Buckaroo\BuckarooClient;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class BuckarooServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerBuckarooClient();
        $this->registerRoutes();
    }

    private function registerBuckarooClient()
    {
        $this->app->singleton('buckaroo', function (Container $app) {
            return new BuckarooWrapper($app);
        });

        $this->app->alias('buckaroo', BuckarooClient::class);
    }

    protected function registerRoutes()
    {
        Route::middleware(['api'])
            ->prefix('api/buckaroo/v1')
            ->name('buckaroo_api.')
            ->group(base_path('app/Buckaroo/Routes/buckaroo_api.php'));
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function provides()
    {
        return [
            'buckaroo'
        ];
    }
}
