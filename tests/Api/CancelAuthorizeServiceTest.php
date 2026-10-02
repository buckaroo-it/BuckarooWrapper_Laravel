<?php

use Buckaroo\Laravel\Api\CancelAuthorizeService;
use Buckaroo\Laravel\Events\VoidTransactionCompleted;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([VoidTransactionCompleted::class]));

function cancelAuthorizeResponse(): array
{
    return [
        'Key' => 'CA1',
        'Status' => ['Code' => ['Code' => 190], 'SubCode' => null],
        'ServiceCode' => 'creditcard',
        'Invoice' => 'INV-1',
        'IsTest' => true,
        'Currency' => 'EUR',
        'AmountCredit' => 25,
        'RelatedTransactions' => [['RelationType' => 'partialpayment', 'RelatedTransactionKey' => 'GRP1']],
    ];
}

it('cancels an authorization at Buckaroo', function () {
    $api = fakeBuckarooApi(cancelAuthorizeResponse());

    CancelAuthorizeService::make(PaymentMethodFactory::make('creditcard')->setPayload([
        'amountCredit' => 25,
        'originalTransactionKey' => 'AU1',
    ]))->void();

    expect($api->calls[0]['method'])->toBe('creditcard');
    expect($api->calls[0]['action'])->toBe('cancelAuthorize');
    expect($api->calls[0]['payload']['originalTransactionKey'])->toBe('AU1');
    expect($api->calls[0]['payload']['returnURL'])->toBe('http://localhost/buckaroo/return');
    expect($api->calls[0]['payload']['pushURL'])->toBe('http://localhost/buckaroo/push');
});

it('stores the cancellation and reports it completed', function () {
    fakeBuckarooApi(cancelAuthorizeResponse());

    [$response, $void] = CancelAuthorizeService::make(PaymentMethodFactory::make('creditcard'))->void();

    $void->refresh();
    expect($void->transaction_key)->toBe('CA1');
    expect($void->status)->toBe('paid');
    expect($void->service_action)->toBe('cancelAuthorize');
    expect((float) $void->amount)->toBe(25.0);
    expect($void->related_transaction_key)->toBe('GRP1');
    Event::assertDispatched(
        VoidTransactionCompleted::class,
        fn (VoidTransactionCompleted $event) => $event->buckarooTransaction->is($void) && $event->transaction === $response
    );
});
