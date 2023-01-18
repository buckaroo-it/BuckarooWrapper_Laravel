<?php

namespace Buckaroo\BuckarooWrapper;

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
        $this->publishes([
            __DIR__ . '/../config/buckaroo.php' => config_path('buckaroo.php')
        ]);
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
