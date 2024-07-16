<?php

namespace Buckaroo\Laravel\PaymentMethods\Bancontact;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class Bancontact extends PaymentGatewayHandler
{
    protected ?string $serviceCode = 'bancontactmrcash';

    public function getPayAction(): ?string
    {
        if ($this->shouldAuthorize) {
            return 'authorize';
        }

        return parent::getPayAction();
    }
}
