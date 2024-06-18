<?php

namespace Buckaroo\Laravel\PaymentMethods\Ideal;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class Ideal extends PaymentGatewayHandler
{
    public function getPayload(): array
    {
        return [
            'servicesSelectableByClient' => 'ideal',
            'continueOnIncomplete' => '1',
        ];
    }
}
