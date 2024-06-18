<?php

namespace Buckaroo\Laravel\DTO;

class Customer
{
    public ?string $birthDate = null;
    public ?string $email;
    public ?string $locale = null;
    public ?string $phoneNumber;
    public ?string $category = null;
    public ?string $chamberOfCommerce = null;
    public ?string $customerNumber = null;
    public BillingAddress $billingAddress;
    public ?BillingAddress $shippingAddress;

    public function __construct(
        BillingAddress  $billingAddress,
        ?BillingAddress $shippingAddress,
        ?string         $email,
        ?string         $birthDate,
        ?string         $locale,
        ?string         $phoneNumber = null,
        ?string         $category = null,
        ?string         $chamberOfCommerce = null,
        ?string         $customerNumber = null,
    )
    {
        $this->birthDate = $birthDate;
        $this->email = $email;
        $this->locale = $locale;
        $this->billingAddress = $billingAddress;
        $this->shippingAddress = $shippingAddress;
        $this->phoneNumber = $phoneNumber;
        $this->category = $category;
        $this->chamberOfCommerce = $chamberOfCommerce;
        $this->customerNumber = $customerNumber;
    }
}
