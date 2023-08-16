<?php

namespace Buckaroo\Laravel\Wrappers;

use Buckaroo\BuckarooClient;

class BuckarooWrapper
{
    public function api()
    {
         return new BuckarooClient(env('BPE_WEBSITE_KEY'), env('BPE_SECRET_KEY'), env('BPE_MODE'));
    }
}
