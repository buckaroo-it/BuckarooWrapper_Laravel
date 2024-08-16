<?php

namespace Buckaroo\Laravel\Tests;

use Buckaroo\BuckarooClient;
use Dotenv\Dotenv;
use GrahamCampbell\TestBench\AbstractPackageTestCase;

abstract class TestCase extends AbstractPackageTestCase
{
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(getcwd());
        $dotenv->load();

        $this->buckaroo = new BuckarooClient($_ENV['BPE_WEBSITE_KEY'], $_ENV['BPE_SECRET_KEY']);

        parent::__construct();
    }
}
