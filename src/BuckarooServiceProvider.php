<?php

namespace Buckaroo\Laravel;

use Illuminate\Support\ServiceProvider;

class BuckarooServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
           // __DIR__ . '/Payments' =>  base_path('app/Http/Requests/Buckaroo'),
        $this->publishMigrations();
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    }

    private function publishMigrations(){
        $this->publishes([
            __DIR__ . '/database/migrations/create_buckaroo_transactions_table.php.stub' => database_path('migrations/2023_01_31_084950_create_buckaroo_transactions_table.php'),
        ], 'migrations');
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


}
