<?php

namespace Buckaroo\Laravel\DTO;

class RefundSession
{
    public string $currency;
    public string $amount;

    public function __construct(
        string $currency,
        string $amount,

    )
    {
        $this->currency = $currency;
        $this->amount = $amount;
    }
}
