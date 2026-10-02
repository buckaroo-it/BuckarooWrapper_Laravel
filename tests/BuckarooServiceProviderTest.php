<?php

use Buckaroo\Laravel\BuckarooServiceProvider;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Laravel\Wrappers\BuckarooClient;
use Buckaroo\Laravel\Wrappers\BuckarooManager;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

function rebootProvider(array $config): void
{
    config($config);
    app('router')->setRoutes(new RouteCollection());

    (new BuckarooServiceProvider(app()))->boot();

    app('router')->getRoutes()->refreshNameLookups();
}

it('loads the package defaults when the config is not published', function () {
    expect(config('buckaroo.transaction_model'))->toBe(BuckarooTransaction::class);
    expect(config('buckaroo.routes'))->toBe(['load' => true, 'prefix' => 'buckaroo']);
    expect(route('buckaroo.push'))->toBe('http://localhost/buckaroo/push');
    expect(route('buckaroo.return'))->toBe('http://localhost/buckaroo/return');
});

it('registers the callback routes under the configured prefix', function () {
    rebootProvider(['buckaroo.routes.prefix' => 'payments']);

    expect(route('buckaroo.push'))->toBe('http://localhost/payments/push');
    expect(Route::getRoutes()->getByName('buckaroo.push')->methods())->toBe(['POST']);
    expect(Route::getRoutes()->getByName('buckaroo.return')->methods())->toBe(['POST', 'GET', 'HEAD']);
});

it('skips the callback routes when route loading is disabled', function () {
    rebootProvider(['buckaroo.routes.load' => false]);

    expect(Route::has('buckaroo.push'))->toBeFalse();
    expect(Route::has('buckaroo.return'))->toBeFalse();
});

it('shares one manager and one API client', function () {
    expect(app('buckaroo'))->toBeInstanceOf(BuckarooManager::class);
    expect(app(BuckarooManager::class))->toBe(app('buckaroo'));
    expect(Buckaroo::getFacadeRoot())->toBe(app('buckaroo'));
    expect(app('buckaroo.api'))->toBeInstanceOf(BuckarooClient::class);
    expect(app(BuckarooClient::class))->toBe(app('buckaroo.api'));
});

it('creates the transactions table from the package migration', function () {
    expect(Schema::hasTable('buckaroo_transactions'))->toBeTrue();
});

it('registers the publish command', function () {
    expect(Artisan::all())->toHaveKey('buckaroo:publish');
});

it('publishes the config, routes and migrations', function () {
    $root = dirname(__DIR__);

    expect(ServiceProvider::pathsToPublish(BuckarooServiceProvider::class, 'buckaroo-config'))
        ->toBe([$root . '/src/../config' => base_path('config')]);
    expect(ServiceProvider::pathsToPublish(BuckarooServiceProvider::class, 'buckaroo-routes'))
        ->toBe([$root . '/src/../routes' => base_path('routes')]);
    expect(ServiceProvider::pathsToPublish(BuckarooServiceProvider::class, 'buckaroo-database'))
        ->toBe([$root . '/src/../database' => base_path('database')]);
    expect(ServiceProvider::pathsToPublish(BuckarooServiceProvider::class, 'buckaroo'))->toHaveCount(3);
});

it('still trims input on routes other than the callbacks', function () {
    Route::post('/echo', fn (Request $request) => $request->input('name'));

    $this->post('/echo', ['name' => ' Jan '])->assertSee('Jan')->assertDontSee(' Jan ');
});
