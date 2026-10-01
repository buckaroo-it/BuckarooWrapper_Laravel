<?php

namespace Buckaroo\Laravel\Wrappers;

use BadMethodCallException;
use Buckaroo\BuckarooClient as BaseBuckarooClient;
use Buckaroo\Config\Config;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Illuminate\Contracts\Config\Repository;
use RuntimeException;

class BuckarooClient
{
    protected Repository $config;
    protected ?BaseBuckarooClient $buckarooClient = null;

    public function __construct(Repository $config)
    {
        $this->config = $config;

        $websiteKey = $this->config->get('buckaroo.website_key');
        $secretKey = $this->config->get('buckaroo.secret_key');

        if (!empty($websiteKey) && !empty($secretKey)) {
            $this->setBuckarooClient($websiteKey, $secretKey, $this->config->get('buckaroo.mode'));
        }
    }

    public function setBuckarooClient(string|Config $websiteKey, ?string $secretKey = null, ?string $mode = null): static
    {
        $this->buckarooClient = new BaseBuckarooClient($websiteKey, $secretKey, $mode);

        return $this;
    }

    public function getClientConfig(): ?Config
    {
        return $this->client()->client()->config();
    }

    public function client()
    {
        if (!$this->buckarooClient) {
            throw new RuntimeException('Buckaroo keys are not configured.');
        }

        return $this->buckarooClient;
    }

    public function validateBody(array|string $payload, $authHeader = '', $url = ''): bool
    {
        if (!$this->buckarooClient) {
            return false;
        }

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
        $client = $this->client();

        if (!method_exists($client, $name)) {
            throw new BadMethodCallException("Method {$name} does not exist.");
        }

        return $client->{$name}(...$arguments);
    }
}
