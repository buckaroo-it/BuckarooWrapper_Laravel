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

        // The SDK refuses empty credentials, so the client is only built once both are set.
        if (filled($this->config->get('buckaroo.website_key')) && filled($this->config->get('buckaroo.secret_key'))) {
            $this->setBuckarooClient(
                $this->config->get('buckaroo.website_key'),
                $this->config->get('buckaroo.secret_key'),
                $this->config->get('buckaroo.mode')
            );
        }
    }

    public function setBuckarooClient(string|Config $websiteKey, ?string $secretKey = null, ?string $mode = null): static
    {
        $this->buckarooClient = new BaseBuckarooClient($websiteKey, $secretKey, $mode);

        return $this;
    }

    public function getClientConfig(): ?Config
    {
        return $this->buckarooClient?->client()->config();
    }

    public function client()
    {
        return $this->buckarooClient;
    }

    public function validateBody(array|string $payload, $authHeader = '', $url = ''): bool
    {
        if (blank($this->getClientConfig()?->secretKey())) {
            return false;
        }

        $data = is_string($payload) ? json_decode($payload, true) : $payload;

        if (is_array($data) && $this->hasResplittableFormValue($data)) {
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

    /**
     * The form signature joins signed `key=value` pairs without a separator, so a signed
     * value that contains `brq_x=`, `add_x=` or `cust_x=` can be re-split into other fields
     * under the same signature. JSON replies are signed over the whole body and are skipped,
     * using the same check as the SDK reply handler.
     */
    protected function hasResplittableFormValue(array $data): bool
    {
        if (array_key_exists('Transaction', $data) || array_key_exists('DataRequest', $data)) {
            return false;
        }

        foreach ($data as $key => $value) {
            if (
                is_string($value)
                && in_array(explode('_', strtolower((string) $key))[0], ['brq', 'add', 'cust'], true)
                && preg_match('/(brq|add|cust)_[^=]*=/i', html_entity_decode($value))
            ) {
                return true;
            }
        }

        return false;
    }

    public function __call($name, $arguments)
    {
        if (!method_exists(BaseBuckarooClient::class, $name)) {
            throw new BadMethodCallException("Method {$name} does not exist.");
        }

        if (!$this->buckarooClient) {
            throw new RuntimeException('Buckaroo credentials are missing. Set BPE_WEBSITE_KEY and BPE_SECRET_KEY.');
        }

        return $this->buckarooClient->{$name}(...$arguments);
    }
}
