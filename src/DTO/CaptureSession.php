<?php

namespace Buckaroo\Laravel\DTO;

class CaptureSession
{
    public string $currency;
    public string $paymentMethod;
    public string $amount;
    public bool $test;

    public function __construct(
        string $paymentMethod,
        string $currency,
        string $amount,
        bool   $test,
    )
    {
        $this->paymentMethod = $paymentMethod;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->test = $test;
    }
}
