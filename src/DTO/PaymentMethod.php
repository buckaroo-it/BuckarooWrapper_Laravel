<?php

namespace Buckaroo\Laravel\DTO;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class PaymentMethod
{
    public string $serviceCode;
    public ?PaymentMethod $parent;
    public ?bool $customizable;
    public ?array $configs = [];

    public function __construct(
        string         $serviceCode,
        ?PaymentMethod $parent = null,
        ?bool          $customizable = false,
        ?array         $configs = [],
    )
    {
        $this->serviceCode = $serviceCode;
        $this->parent = $parent;
        $this->customizable = $customizable;
        $this->configs = $configs;
    }

    public function getInstance()
    {
        return PaymentGatewayHandler::newBuckarooPaymentMethod($this);
    }

    public function hasConfig(string $key): bool
    {
        return isset($this->configs[$key]);
    }

    public function getConfig(string $key, $default = null)
    {
        return data_get($this->configs, $key, $default);
    }
}
