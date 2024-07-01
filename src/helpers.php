<?php

use Buckaroo\Laravel\Wrappers\BuckarooClient;

if (!function_exists('buckaroo')) {
    /**
     * @return BuckarooClient
     */
    function buckaroo()
    {
        return app('buckaroo.client');
    }
}
