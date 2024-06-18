<?php

namespace Buckaroo\Laravel\PaymentMethods\PayByBank;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class PayByBank extends PaymentGatewayHandler
{
    public function getLogo()
    {
        return $this->buildAssetUrl($this->serviceCode, 'gif');
    }

    public function getPayload(): array
    {
        return [
            'continueOnIncomplete' => 1,
        ];
    }
}
