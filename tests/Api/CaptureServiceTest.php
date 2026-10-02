<?php

use Buckaroo\Laravel\Api\CaptureService;
use Buckaroo\Laravel\Events\CaptureTransactionCompleted;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([CaptureTransactionCompleted::class]));

function captureResponse(): array
{
    return [
        'Key' => 'CP1',
        'Status' => ['Code' => ['Code' => 190], 'SubCode' => null],
        'ServiceCode' => 'creditcard',
        'Invoice' => 'INV-1',
        'IsTest' => true,
        'Currency' => 'EUR',
        'AmountDebit' => 25,
        'RelatedTransactions' => [['RelationType' => 'partialpayment', 'RelatedTransactionKey' => 'GRP1']],
    ];
}

it('captures an authorized payment at Buckaroo', function () {
    $api = fakeBuckarooApi(captureResponse());

    CaptureService::make(PaymentMethodFactory::make('creditcard')->setPayload([
        'amountDebit' => 25,
        'originalTransactionKey' => 'AU1',
    ]))->capture();

    expect($api->calls[0]['method'])->toBe('creditcard');
    expect($api->calls[0]['action'])->toBe('capture');
    expect($api->calls[0]['payload']['originalTransactionKey'])->toBe('AU1');
    expect($api->calls[0]['payload']['returnURL'])->toBe('http://localhost/buckaroo/return');
    expect($api->calls[0]['payload']['pushURL'])->toBe('http://localhost/buckaroo/push');
});

it('stores the capture and reports it completed', function () {
    fakeBuckarooApi(captureResponse());

    [$response, $capture] = CaptureService::make(PaymentMethodFactory::make('creditcard'))->capture();

    $capture->refresh();
    expect($capture->transaction_key)->toBe('CP1');
    expect($capture->status)->toBe('paid');
    expect((float) $capture->amount)->toBe(25.0);
    expect($capture->service_action)->toBe('pay');
    expect($capture->related_transaction_key)->toBe('GRP1');
    Event::assertDispatched(
        CaptureTransactionCompleted::class,
        fn (CaptureTransactionCompleted $event) => $event->buckarooTransaction->is($capture) && $event->transaction === $response
    );
});
