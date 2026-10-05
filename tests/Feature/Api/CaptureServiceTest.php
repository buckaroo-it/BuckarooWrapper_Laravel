<?php

use Buckaroo\Laravel\Api\CaptureService;
use Buckaroo\Laravel\Events\CaptureTransactionCompleted;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;
use Buckaroo\Laravel\Tests\Support\BuckarooMockRequest;
use Buckaroo\Resources\Constants\Endpoints;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([CaptureTransactionCompleted::class]));

function captureResponse(): array
{
    return [
        'Key' => 'CP1',
        'Status' => ['Code' => ['Code' => 190], 'SubCode' => null],
        'ServiceCode' => 'visa',
        'Invoice' => 'INV-1',
        'IsTest' => true,
        'Currency' => 'EUR',
        'AmountDebit' => 25,
        'RelatedTransactions' => [['RelationType' => 'partialpayment', 'RelatedTransactionKey' => 'GRP1']],
    ];
}

function visaCapture(): CaptureService
{
    return CaptureService::make(PaymentMethodFactory::make('creditcard')->setPayload([
        'amountDebit' => 25,
        'invoice' => 'INV-1',
        'originalTransactionKey' => 'AU1',
        'name' => 'visa',
    ]));
}

it('captures an authorized payment at Buckaroo', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', captureResponse())
            ->expectJsonSubset([
                'AmountDebit' => 25,
                'OriginalTransactionKey' => 'AU1',
                'ReturnURL' => 'http://localhost/buckaroo/return',
                'PushURL' => 'http://localhost/buckaroo/push',
                'Services' => ['ServiceList' => [['name' => 'visa', 'action' => 'Capture']]],
            ]),
    ]);

    visaCapture()->capture();

    $this->helpers->mockBuckaroo()->assertAllConsumed();
});

it('stores the capture and reports it completed', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', captureResponse()),
    ]);

    [$response, $capture] = visaCapture()->capture();

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
