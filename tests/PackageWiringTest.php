<?php

namespace Buckaroo\Laravel\Tests;

use Buckaroo\Laravel\Wrappers\BuckarooClient;
use Illuminate\Support\Facades\Route;

class PackageWiringTest extends TestCase
{
    public function test_routes_load_without_published_config(): void
    {
        $this->assertTrue(Route::has('buckaroo.push'));
        $this->assertNotSame(404, $this->post('/buckaroo/push')->getStatusCode());
    }

    public function test_helper_returns_buckaroo_client(): void
    {
        $this->assertInstanceOf(BuckarooClient::class, buckaroo());
    }
}
