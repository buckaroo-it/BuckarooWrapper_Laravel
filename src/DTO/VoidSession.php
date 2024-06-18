<?php

namespace Buckaroo\Laravel\DTO;

class VoidSession
{
    public string $paymentMethod;
    public bool $test;

    public function __construct(
        string $paymentMethod,
        bool   $test,
    )
    {
        $this->paymentMethod = $paymentMethod;
        $this->test = $test;
    }
}
