<?php

namespace Buckaroo\Laravel\PaymentMethods\Przelewy24;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Buckaroo\Laravel\Traits\HasCustomerDetails;

class Przelewy24 extends PaymentGatewayHandler
{
    use HasCustomerDetails;

    public function getPayload(): array
    {
        $customer = $this->getCustomer();
        $billingAddress = $this->getBillingAddress();

        return [
            'email' => $customer->email ?? '',
            'customer' => [
                'firstName' => $billingAddress->firstName ?? '',
                'lastName' => $billingAddress->lastName ?? '',
            ],
        ];
    }
}
