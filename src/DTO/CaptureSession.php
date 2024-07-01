<?php

namespace Buckaroo\Laravel\DTO;

class CaptureSession extends BaseData
{
    public string $currency;
    public string $paymentMethod;
    public float $amount;
    public bool $isTest;

    public function __construct(
        string $paymentMethod,
        string $currency,
        float $amount,
        bool $isTest,
    ) {
        $this->paymentMethod = $paymentMethod;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->isTest = $isTest;
    }
}
