<?php

use Buckaroo\Laravel\Tests\Support\MockBuckaroo;
use Buckaroo\Laravel\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()
    ->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit')
    ->beforeEach(function () {
        $mockBuckaroo = new MockBuckaroo();
        $mockBuckaroo->install();
        app()->instance(MockBuckaroo::class, $mockBuckaroo);
    });
