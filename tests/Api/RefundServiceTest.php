<?php

use Buckaroo\Laravel\Api\RefundService;
use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;
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
    $api = fakeBuckarooApi(refundResponse());

    idealRefund()->refund();

    expect($api->calls[0]['method'])->toBe('ideal');
    expect($api->calls[0]['action'])->toBe('refund');
    expect($api->calls[0]['payload']['originalTransactionKey'])->toBe('TX1');
    expect($api->calls[0]['payload']['returnURL'])->toBe('http://localhost/buckaroo/return');
    expect($api->calls[0]['payload']['pushURL'])->toBe('http://localhost/buckaroo/push');
});

it('stores the refund as a negative amount linked to the payment', function () {
    $payment = createTransaction(['status' => 'paid', 'status_code' => '190']);
    fakeBuckarooApi(refundResponse());

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
    fakeBuckarooApi(refundResponse($statusCode));

    [$response, $refund] = idealRefund()->refund();

    Event::assertDispatched(
        RefundTransactionCompleted::class,
        fn (RefundTransactionCompleted $event) => $event->buckarooTransaction->is($refund) && $event->transaction === $response
    );
})->with(['processed' => [190], 'pending approval' => [794]]);
