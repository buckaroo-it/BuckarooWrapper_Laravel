<?php

namespace Buckaroo\Laravel\PaymentMethods\Bancontact;

use App\Services\Buckaroo\PaymentMethods\PaymentGatewayHandler;

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
