<?php

namespace Buckaroo\Laravel\PaymentMethods\Trustly;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Buckaroo\Laravel\Traits\HasCustomerDetails;

class Trustly extends PaymentGatewayHandler
{
    use HasCustomerDetails;

    public function getPayload(): array
    {
        $billingAddress = $this->getBillingAddress();

        return [
            'country' => $billingAddress->countryCode ?? '',
            'customer' => [
                'firstName' => $billingAddress->firstName ?? '',
                'lastName' => $billingAddress->lastName ?? '',
            ],
        ];
    }
}
