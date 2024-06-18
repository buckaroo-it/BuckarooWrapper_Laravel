<?php

namespace Buckaroo\Laravel\DTO;

class PaymentSession
{
    public string $currency;
    public string $amount;
    public ?string $order;
    public ?string $invoice;
    public bool $test;
    public string $paymentMethod;
    public bool $isAuthorized;
    public string $kind;
    public Customer $customer;
    public ?array $products = [];

    public function __construct(
        string   $paymentMethod,
        string $currency,
        string $amount,
        string   $kind,
        bool     $isAuthorized,
        ?string  $order,
        ?string  $invoice,
        bool     $test,
        Customer $customer,
        ?array   $products,
    )
    {
        $this->paymentMethod = $paymentMethod;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->order = $order;
        $this->invoice = $invoice;
        $this->kind = $kind;
        $this->isAuthorized = $isAuthorized;
        $this->test = $test;
        $this->customer = $customer;
        $this->products = $products;
    }
}
