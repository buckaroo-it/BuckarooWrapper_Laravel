<?php

namespace Buckaroo\Laravel\PaymentMethods\Blik;

use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class Blik extends PaymentGatewayHandler
{
    protected function buildAssetUrl(string $serviceCode, string $extension = 'svg'): string
    {
        return parent::buildAssetUrl($serviceCode, 'png');
    }
}
