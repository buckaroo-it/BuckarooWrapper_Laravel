<?php

namespace Buckaroo\Laravel\PaymentMethods\Bancontact;

use Buckaroo\Laravel\Contracts\Capturable;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class Bancontact extends PaymentGatewayHandler implements Capturable
{
    public function getPayAction(): ?string
    {
        $paymentSessionDto = $this->paymentSession->toDto();
        if (
            $paymentSessionDto->kind == 'authorization' &&
            !$paymentSessionDto->isAuthorized
        ) {
            return 'authorize';
        }

        return parent::getPayAction();
    }

    public function getServiceCode(): ?string
    {
        return 'bancontactmrcash';
    }

    public function getCapturePayload(): array
    {
        return [];
    }
}
