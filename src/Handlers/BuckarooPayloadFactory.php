<?php

namespace Buckaroo\Laravel\Handlers;

class BuckarooPayloadFactory
{
    public static function getPayload(string $serviceCode)
    {
        return PaymentGatewayHandler::make($serviceCode);
    }
}
