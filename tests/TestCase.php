<?php

namespace Buckaroo\Laravel\Tests;

use Buckaroo\Laravel\BuckarooServiceProvider;
use Buckaroo\Laravel\Tests\Support\TestHelpers;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use Mockery;

abstract class TestCase extends AbstractPackageTestCase
{
    public const WEBSITE_KEY = 'test-website-key';

    public const SECRET_KEY = 'test-secret-key';

    protected TestHelpers $helpers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->helpers = new TestHelpers();
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('buckaroo.website_key', self::WEBSITE_KEY);
        $app->config->set('buckaroo.secret_key', self::SECRET_KEY);
        $app->config->set('buckaroo.mode', 'test');
    }

    protected static function getServiceProviderClass(): string
    {
        return BuckarooServiceProvider::class;
    }
}
