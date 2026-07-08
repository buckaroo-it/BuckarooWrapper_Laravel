<?php

namespace Buckaroo\Laravel\Tests;

use Buckaroo\Laravel\BuckarooServiceProvider;
use Dotenv\Dotenv;
use GrahamCampbell\TestBench\AbstractPackageTestCase;

abstract class TestCase extends AbstractPackageTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Dotenv::createImmutable(getcwd())->safeLoad();
    }

    protected static function getServiceProviderClass(): string
    {
        return BuckarooServiceProvider::class;
    }
}
