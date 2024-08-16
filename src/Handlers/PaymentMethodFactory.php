<?php

namespace Buckaroo\Laravel\Handlers;

class PaymentMethodFactory
{
    public static function make(string $serviceCode)
    {
        return PaymentGatewayHandler::make($serviceCode);
    }
}
