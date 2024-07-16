<?php

namespace Buckaroo\Laravel\PaymentMethods\Afterpay;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class Afterpay extends PaymentGatewayHandler
{
    protected ?string $serviceCode = 'afterpay';

    public function getPayAction(): ?string
    {
        if ($this->shouldAuthorize) {
            return 'authorize';
        }

        return parent::getPayAction();
    }
}
