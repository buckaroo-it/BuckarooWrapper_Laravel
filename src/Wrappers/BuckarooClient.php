<?php

namespace Buckaroo\Laravel\Wrappers;

use BadMethodCallException;
use Buckaroo\BuckarooClient as BaseBuckarooClient;
use Buckaroo\Config\Config;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Illuminate\Contracts\Config\Repository;

class BuckarooClient
{
    protected Repository $config;
    protected BaseBuckarooClient $buckarooClient;

    public function __construct(Repository $config)
    {
        $this->config = $config;

        $this->setBuckarooClient(
            $this->config->get('buckaroo.website_key'),
            $this->config->get('buckaroo.secret_key'),
            $this->config->get('buckaroo.mode')
        );
    }

    public function setBuckarooClient(string|Config $websiteKey, ?string $secretKey = null, ?string $mode = null): static
    {
        $this->buckarooClient = new BaseBuckarooClient($websiteKey, $secretKey, $mode);

        return $this;
    }

    public function getClientConfig(): ?Config
    {
        return $this->buckarooClient->client()->config();
    }

    public function client()
    {
        return $this->buckarooClient;
    }

    public function validateBody(array|string $payload, $authHeader = '', $url = ''): bool
    {
        $replyHandler = new ReplyHandler(
            $this->getClientConfig(),
            $payload,
            $authHeader ?? '',
            $url
        );

        return $replyHandler->validate()->isValid();
    }

    public function __call($name, $arguments)
    {
        if (!method_exists($this->buckarooClient, $name)) {
            throw new BadMethodCallException("Method {$name} does not exist.");
        }

        return $this->buckarooClient->{$name}(...$arguments);
    }
}
