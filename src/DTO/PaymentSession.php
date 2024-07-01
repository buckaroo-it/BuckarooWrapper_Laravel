<?php

namespace Buckaroo\Laravel\DTO;

class PaymentSession extends BaseData
{
    public string $currency;
    public float $amount;
    public ?string $order;
    public ?string $invoice;
    public bool $isTest;
    public string $paymentMethod;
    public bool $isAuthorized = false;
    public string $kind;
    public ?Customer $customer = null;
    public array $products = [];

    public function __construct(
        string $paymentMethod,
        string $currency,
        float $amount,
        bool $isTest,
        string $kind,
        ?bool $isAuthorized = false,
        ?string $order = '',
        ?string $invoice = '',
        ?Customer $customer = null,
        array $products = [],
    ) {
        $this->paymentMethod = $paymentMethod;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->order = $order;
        $this->invoice = $invoice;
        $this->kind = $kind;
        $this->isAuthorized = $isAuthorized;
        $this->isTest = $isTest;
        $this->customer = $customer;
        $this->products = $products;
    }
}
