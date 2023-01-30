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
        $this->publishes([
            __DIR__ . '/Payments' =>  base_path('app/Http/Requests/Buckaroo'),
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
