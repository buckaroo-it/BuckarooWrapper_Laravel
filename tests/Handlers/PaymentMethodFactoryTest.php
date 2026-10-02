<?php

use Buckaroo\Laravel\Handlers\PaymentGatewayHandler;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;

it('makes a gateway handler for the service code', function () {
    $handler = PaymentMethodFactory::make('ideal');

    expect($handler)->toBeInstanceOf(PaymentGatewayHandler::class);
    expect($handler->getServiceCode())->toBe('ideal');
    expect($handler->getPayAction())->toBe('pay');
});
