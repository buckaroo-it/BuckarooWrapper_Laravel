<?php

namespace Buckaroo\BuckarooWrapper;

use Buckaroo\BuckarooClient;

class BaseService
{
    public  function client()
    {
        try {
            return new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
