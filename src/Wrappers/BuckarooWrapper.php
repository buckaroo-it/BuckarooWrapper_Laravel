<?php

namespace Buckaroo\Laravel\Wrappers;

use Illuminate\Contracts\Config\Repository;


use Buckaroo\BuckarooClient;

class BuckarooWrapper
{
    protected Repository $config;
    protected BuckarooClient $buckarooClient;

    public function __construct(Repository $config)
    {
        $this->config = $config;

        $websiteKey = $this->config->get('buckaroo.website_key');
        $secretKey = $this->config->get('buckaroo.secret_key');
        $mode = $this->config->get('buckaroo.mode');

        $this->buckarooClient = new BuckarooClient($websiteKey, $secretKey, $mode);
    }

    public function test()
    {
        dd($this->buckarooClient);
    }
}
