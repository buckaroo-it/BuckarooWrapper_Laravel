<?php

namespace Buckaroo\Laravel\Handlers;

use Buckaroo\Laravel\PaymentMethods;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class BuckarooPayloadFactory
{
    protected const array HANDLERS = [
        'bancontactmrcash' => PaymentMethods\Bancontact\Bancontact::class,
        'afterpay' => PaymentMethods\Afterpay\Afterpay::class,
    ];

    public static function getPayload(string $serviceCode)
    {
        if (!isset(self::HANDLERS[$serviceCode])) {
            return PaymentGatewayHandler::make($serviceCode);
        }

        $class = self::HANDLERS[$serviceCode];

        return new $class();
    }
}
