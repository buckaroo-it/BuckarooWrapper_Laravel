<?php

use Buckaroo\Laravel\Wrappers\BuckarooClient;

it('gives the Buckaroo API client through the buckaroo() helper', function () {
    expect(buckaroo())->toBeInstanceOf(BuckarooClient::class);
});
