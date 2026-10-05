<?php

use Buckaroo\Laravel\Api\CancelAuthorizeService;
use Buckaroo\Laravel\Events\VoidTransactionCompleted;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;
use Buckaroo\Laravel\Tests\Support\BuckarooMockRequest;
use Buckaroo\Resources\Constants\Endpoints;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([VoidTransactionCompleted::class]));

function cancelAuthorizeResponse(): array
{
    return [
        'Key' => 'CA1',
        'Status' => ['Code' => ['Code' => 190], 'SubCode' => null],
        'ServiceCode' => 'visa',
        'Invoice' => 'INV-1',
        'IsTest' => true,
        'Currency' => 'EUR',
        'AmountCredit' => 25,
        'RelatedTransactions' => [['RelationType' => 'partialpayment', 'RelatedTransactionKey' => 'GRP1']],
    ];
}

function visaCancelAuthorize(): CancelAuthorizeService
{
    return CancelAuthorizeService::make(PaymentMethodFactory::make('creditcard')->setPayload([
        'amountCredit' => 25,
        'invoice' => 'INV-1',
        'originalTransactionKey' => 'AU1',
        'name' => 'visa',
    ]));
}

it('cancels an authorization at Buckaroo', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', cancelAuthorizeResponse())
            ->expectJsonSubset([
                'AmountCredit' => 25,
                'OriginalTransactionKey' => 'AU1',
                'ReturnURL' => 'http://localhost/buckaroo/return',
                'PushURL' => 'http://localhost/buckaroo/push',
                'Services' => ['ServiceList' => [['name' => 'visa', 'action' => 'CancelAuthorize']]],
            ]),
    ]);

    visaCancelAuthorize()->void();

    $this->helpers->mockBuckaroo()->assertAllConsumed();
});

it('stores the cancellation and reports it completed', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', cancelAuthorizeResponse()),
    ]);

    [$response, $void] = visaCancelAuthorize()->void();

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
