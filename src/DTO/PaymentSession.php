<?php

namespace Buckaroo\Laravel\DTO;

class PaymentSession
{
    public string $currency;
    public string $amount;
    public string $order;
    public string $invoice;

    public function __construct(
        string $currency,
        string $amount,
        string $order,
        string $invoice,

    )
    {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->order = $order;
        $this->invoice = $invoice;
    }
}
