<?php

namespace Buckaroo\Laravel\PaymentMethods\Afterpay;

use App\Services\Buckaroo\PaymentMethods\PaymentGatewayHandler;

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
