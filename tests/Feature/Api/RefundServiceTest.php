<?php

use Buckaroo\Laravel\Api\RefundService;
use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;
use Buckaroo\Laravel\Tests\Support\BuckarooMockRequest;
use Buckaroo\Resources\Constants\Endpoints;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([RefundTransactionCompleted::class]));

function refundResponse(int $statusCode = 190): array
{
    return [
        'Key' => 'RF1',
        'Status' => ['Code' => ['Code' => $statusCode], 'SubCode' => null],
        'ServiceCode' => 'ideal',
        'Invoice' => 'INV-1',
        'IsTest' => true,
        'Currency' => 'EUR',
        'AmountCredit' => 4.25,
        'RelatedTransactions' => [['RelationType' => 'refund', 'RelatedTransactionKey' => 'TX1']],
    ];
}

function idealRefund(): RefundService
{
    return RefundService::make(PaymentMethodFactory::make('ideal')->setPayload([
        'amountCredit' => 4.25,
        'invoice' => 'INV-1',
        'originalTransactionKey' => 'TX1',
    ]));
}

it('sends the refund to Buckaroo for the original transaction', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', refundResponse())
            ->expectJsonSubset([
                'AmountCredit' => 4.25,
                'Invoice' => 'INV-1',
                'OriginalTransactionKey' => 'TX1',
                'ReturnURL' => 'http://localhost/buckaroo/return',
                'PushURL' => 'http://localhost/buckaroo/push',
                'Services' => ['ServiceList' => [['name' => 'ideal', 'action' => 'Refund']]],
            ]),
    ]);

    idealRefund()->refund();

    $this->helpers->mockBuckaroo()->assertAllConsumed();
});

it('stores the refund as a negative amount linked to the payment', function () {
    $payment = $this->helpers->createTransaction(['status' => 'paid', 'status_code' => '190']);
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', refundResponse()),
    ]);

    [, $refund] = idealRefund()->refund();

    $refund->refresh();
    expect($refund->transaction_key)->toBe('RF1');
    expect($refund->status)->toBe('paid');
    expect($refund->service_action)->toBe('refund');
    expect((float) $refund->amount)->toBe(-4.25);
    expect($refund->relatedTransaction->is($payment))->toBeTrue();
    expect($payment->refunds->pluck('transaction_key')->all())->toBe(['RF1']);
});

it('reports every refund it stores', function (int $statusCode) {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', refundResponse($statusCode)),
    ]);

    [$response, $refund] = idealRefund()->refund();

    Event::assertDispatched(
        RefundTransactionCompleted::class,
        fn (RefundTransactionCompleted $event) => $event->buckarooTransaction->is($refund) && $event->transaction === $response
    );
})->with(['processed' => [190], 'pending approval' => [794]]);
