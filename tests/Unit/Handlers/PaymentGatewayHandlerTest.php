<?php

use Buckaroo\Laravel\Handlers\PaymentGatewayHandler;

it('builds the payload from setters', function () {
    $handler = PaymentGatewayHandler::make('ideal')
        ->setCurrency('EUR')
        ->setAmountDebit(10.5)
        ->setInvoice('INV-1')
        ->setCustomer(['firstName' => 'Jan']);

    expect($handler->getAmountDebit())->toBe(10.5);
    expect($handler->getCustomer())->toBe(['firstName' => 'Jan']);
    expect($handler->toArray())->toBe([
        'returnURL' => 'http://localhost/buckaroo/return',
        'pushURL' => 'http://localhost/buckaroo/push',
        'currency' => 'EUR',
        'amountDebit' => 10.5,
        'invoice' => 'INV-1',
        'customer' => ['firstName' => 'Jan'],
    ]);
});

it('lets the payload override the package return and push URLs', function () {
    $payload = PaymentGatewayHandler::make('ideal')
        ->setReturnURL('https://shop.test/thanks')
        ->setPushURL('https://shop.test/push')
        ->toArray();

    expect($payload['returnURL'])->toBe('https://shop.test/thanks');
    expect($payload['pushURL'])->toBe('https://shop.test/push');
});

it('replaces the whole payload', function () {
    $handler = PaymentGatewayHandler::make('ideal')->setOrder('ORD-1')->setPayload(['invoice' => 'INV-2']);

    expect($handler->getOrder())->toBeNull();
    expect($handler->getInvoice())->toBe('INV-2');
});

it('pays by default and authorizes when asked', function () {
    expect(PaymentGatewayHandler::make('creditcard')->getPayAction())->toBe('pay');
    expect(PaymentGatewayHandler::make('creditcard')->shouldAuthorize()->getPayAction())->toBe('authorize');
    expect(PaymentGatewayHandler::make('creditcard')->shouldAuthorize()->shouldAuthorize(false)->getPayAction())->toBe('pay');
    expect(PaymentGatewayHandler::make('creditcard')->getRefundAction())->toBe('refund');
});

it('rejects methods that are not a setter or getter', function () {
    PaymentGatewayHandler::make('ideal')->refundAll();
})->throws(BadMethodCallException::class, 'Method refundAll does not exist.');
