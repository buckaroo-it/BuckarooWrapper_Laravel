<?php

namespace Buckaroo\Laravel\PaymentMethods\Transfer;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Buckaroo\Laravel\Traits\HasCustomerDetails;

class Transfer extends PaymentGatewayHandler
{
    use HasCustomerDetails;

    public function getPayload(): array
    {
        $billingAddress = $this->getBillingAddress();

        return [
            'customer' => [
                'firstName' => $billingAddress->firstName ?? '',
                'lastName' => $billingAddress->lastName ?? '',
            ],
        ];
    }
}
