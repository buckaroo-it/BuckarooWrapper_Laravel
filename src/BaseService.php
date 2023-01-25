<?php

namespace Buckaroo\Laravel;

use Buckaroo\BuckarooClient;
use Dotenv\Dotenv;

class BaseService
{
    protected $client;

    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(getcwd());
        $dotenv->load();

        $this->client = $this->client();
    }

    public function client()
    {
        try {
            return new BuckarooClient(env('BPE_WEBSITE_KEY'), env('BPE_SECRET_KEY'), env('BPE_MODE'));
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
