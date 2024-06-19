<?php

namespace Buckaroo\Laravel\DTO;

class CaptureSession
{
    public string $currency;
    public string $paymentMethod;
    public string $amount;
    public bool $isTest;

    public function __construct(
        string $paymentMethod,
        string $currency,
        string $amount,
        bool   $isTest,
    )
    {
        $this->paymentMethod = $paymentMethod;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->isTest = $isTest;
    }
}
