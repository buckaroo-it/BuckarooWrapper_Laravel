<?php

namespace Buckaroo\Laravel\Wrappers;

use Buckaroo\Handlers\Reply\ReplyHandler;
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

        $this->setBuckarooClient($websiteKey, $secretKey, $mode);
    }

    public function client()
    {
        return $this->buckarooClient;
    }

    public function method(string $method)
    {
        return $this->buckarooClient->method($method);
    }

    public function confirmCredential(): bool
    {
        return $this->buckarooClient->confirmCredential();
    }

    public function updateCredential(string $websiteKey, string $secretKey, string $mode = 'live')
    {
        return $this->setBuckarooClient($websiteKey, $secretKey, $mode);
    }

    public function setBuckarooClient(string $websiteKey, string $secretKey, string $mode)
    {
        $this->buckarooClient = new BuckarooClient($websiteKey, $secretKey, $mode);

        return $this;
    }

    public function validateBody(array|string $body, $header = '', $url = ''): bool
    {
        $reply_handler = new ReplyHandler($this->buckarooClient->client()->config(), $body,  $header, $url);
        $reply_handler->validate();

        return $reply_handler->isValid();
    }
}
