<?php

namespace Buckaroo\Laravel;

use Buckaroo\BuckarooClient;

class BaseService
{
    protected $client;

    public function __construct()
    {
        $this->client = $this->client();
    }
    public  function client()
    {
        try {
            return new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.website'));
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
