<?php

namespace Buckaroo\Laravel\Handlers\Payload;

class DefaultPayload
{
    protected string $orderNumber;
    protected string $invoiceNumber;
    protected float $amountDebit = 0;
    protected string $currency = 'EUR';
    protected string $description = '';
    protected array $payload = array();

    public function __construct()
    {
        $this->orderNumber = uniqid();
        $this->invoiceNumber = uniqid();
    }

    public function getPayload()
    {
        $payload = array(
            'order'                 => $this->orderNumber,
            'invoice'               => $this->invoiceNumber,
            'amountDebit'           => $this->amountDebit(),
            'currency'              => $this->currency(),
            'description'           => $this->description(),
            'clientIP'              => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '',
            'pushURL'               => route('buckaroo.push'),
            'pushURLFailure'        => route('buckaroo.push'),
            'returnURL'             => route('buckaroo.return'),
            'returnURLCancel'       => route('buckaroo.return-cancel'),
            'returnURLError'        => route('buckaroo.return-error'),
            'returnURLReject'       => route('buckaroo.return-reject')
        );

        $this->payload = array_merge($this->payload, $payload);

        return $this->payload;
    }

    public function orderNumber(string $orderNumber = null)
    {
        if($orderNumber)
        {
            $this->orderNumber = $orderNumber;
        }

        return $this->orderNumber;
    }

    public function invoiceNumber(string $invoiceNumber = null)
    {
        if($invoiceNumber)
        {
            $this->invoiceNumber = $invoiceNumber;
        }

        return $this->invoiceNumber;
    }

    public function amountDebit(float $amountDebit = null): float
    {
        if($amountDebit)
        {
            $this->amountDebit = $amountDebit;
        }

        return $this->amountDebit;
    }

    public function currency(string $currency = null): string
    {
        if($currency)
        {
            $this->currency = $currency;
        }

        return $this->currency;
    }

    public function description(string $description = null): string
    {
        if($description)
        {
            $this->description = $description;
        }

        return $this->description;
    }

    public function __call($method, $args)
    {
        if(method_exists($this, $method))
        {
            return $this->$method($args);
        }

        return $this;
    }
}
