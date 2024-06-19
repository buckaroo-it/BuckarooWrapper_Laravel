<?php

namespace Buckaroo\Laravel\DTO;

class VoidSession
{
    public string $paymentMethod;
    public bool $isTest;

    public function __construct(
        string $paymentMethod,
        bool $isTest,
    ) {
        $this->paymentMethod = $paymentMethod;
        $this->isTest = $isTest;
    }
}
