<?php

use Buckaroo\Laravel\Api\PayService;
use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Laravel\Tests\Support\BuckarooMockRequest;
use Buckaroo\Resources\Constants\Endpoints;
use Buckaroo\Transaction\Response\TransactionResponse;
use Illuminate\Support\Facades\Event;

class ShopTransaction extends BuckarooTransaction
{
    protected $table = 'buckaroo_transactions';
}

beforeEach(fn () => Event::fake([PayTransactionCompleted::class]));

function payResponse(array $fields = []): array
{
    return array_merge([
        'Key' => 'TX1',
        'Status' => [
            'Code' => ['Code' => 190, 'Description' => 'Success'],
            'SubCode' => ['Code' => 'S001', 'Description' => 'Transaction successfully processed'],
        ],
        'RequiredAction' => null,
        'ServiceCode' => 'ideal',
        'Invoice' => 'INV-1',
        'Order' => 'ORD-1',
        'IsTest' => true,
        'Currency' => 'EUR',
        'AmountDebit' => 10.5,
        'RelatedTransactions' => null,
    ], $fields);
}

function idealPayment(): PayService
{
    return PayService::make(PaymentMethodFactory::make('ideal')->setPayload([
        'currency' => 'EUR',
        'amountDebit' => 10.5,
        'invoice' => 'INV-1',
        'order' => 'ORD-1',
    ]));
}

it('sends the payment to Buckaroo with the return and push URLs', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', payResponse())
            ->expectJsonSubset([
                'Currency' => 'EUR',
                'AmountDebit' => 10.5,
                'Invoice' => 'INV-1',
                'Order' => 'ORD-1',
                'ReturnURL' => 'http://localhost/buckaroo/return',
                'PushURL' => 'http://localhost/buckaroo/push',
                'Services' => ['ServiceList' => [['name' => 'ideal', 'action' => 'Pay']]],
            ]),
    ]);

    idealPayment()->pay();

    $this->helpers->mockBuckaroo()->assertAllConsumed();
});

it('stores a processed payment and reports it completed', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', payResponse()),
    ]);

    [$response, $transaction] = idealPayment()->pay();

    expect($response)->toBeInstanceOf(TransactionResponse::class);
    expect($transaction->fresh()->only(['transaction_key', 'status', 'status_code', 'status_subcode', 'payment_method', 'invoice', 'order', 'currency', 'service_action']))->toBe([
        'transaction_key' => 'TX1',
        'status' => 'paid',
        'status_code' => '190',
        'status_subcode' => 'S001',
        'payment_method' => 'ideal',
        'invoice' => 'INV-1',
        'order' => 'ORD-1',
        'currency' => 'EUR',
        'service_action' => 'pay',
    ]);
    expect((float) $transaction->fresh()->amount)->toBe(10.5);
    expect($transaction->fresh()->is_test)->toBeTrue();
    Event::assertDispatched(
        PayTransactionCompleted::class,
        fn (PayTransactionCompleted $event) => $event->buckarooTransaction->is($transaction) && $event->transaction === $response
    );
});

it('waits for the push when the customer must be redirected', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', payResponse([
            'Status' => ['Code' => ['Code' => 790, 'Description' => 'Pending input'], 'SubCode' => null],
            'RequiredAction' => ['RedirectURL' => 'https://testcheckout.buckaroo.nl/html/redirect.ashx?r=ABC', 'Name' => 'Redirect'],
        ])),
    ]);

    [$response, $transaction] = idealPayment()->pay();

    expect($response->hasRedirect())->toBeTrue();
    expect($transaction->status)->toBe('pending');
    Event::assertNotDispatched(PayTransactionCompleted::class);
});

it('authorizes instead of paying when asked', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', payResponse(['ServiceCode' => 'visa']))
            ->expectJsonSubset(['Services' => ['ServiceList' => [['name' => 'visa', 'action' => 'Authorize']]]]),
    ]);

    [, $transaction] = PayService::make(
        PaymentMethodFactory::make('creditcard')
            ->setPayload(['currency' => 'EUR', 'amountDebit' => 25, 'invoice' => 'INV-1', 'name' => 'visa'])
            ->shouldAuthorize()
    )->pay();

    expect($transaction->service_action)->toBe('authorize');
    $this->helpers->mockBuckaroo()->assertAllConsumed();
});

it('links a partial payment to its group transaction', function () {
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', payResponse([
            'RelatedTransactions' => [['RelationType' => 'partialpayment', 'RelatedTransactionKey' => 'GRP1']],
        ])),
    ]);

    [, $transaction] = idealPayment()->pay();

    expect($transaction->related_transaction_key)->toBe('GRP1');
});

it('stores the payment in the configured transaction model', function () {
    config(['buckaroo.transaction_model' => ShopTransaction::class]);
    $this->helpers->mockBuckaroo()->mockTransportRequests([
        BuckarooMockRequest::json('POST', Endpoints::TEST . 'json/Transaction/', payResponse()),
    ]);

    [, $transaction] = idealPayment()->pay();

    expect($transaction)->toBeInstanceOf(ShopTransaction::class);
});
