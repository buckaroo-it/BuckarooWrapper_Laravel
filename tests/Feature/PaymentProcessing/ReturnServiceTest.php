<?php

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\PaymentProcessing\ReturnService;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([PayTransactionCompleted::class]));

function returnService(array $payload): ReturnService
{
    $request = ReplyHandlerRequest::create('/buckaroo/return', 'POST', $payload);

    return ReturnService::make($request);
}

it('completes a pending payment on return when no push arrived yet', function () {
    $transaction = $this->helpers->createTransaction(['service_action' => 'pay']);

    $result = returnService($this->helpers->pushPayloadFormData(['brq_statuscode' => '791']))->handleReturnRequest();

    expect($result->is($transaction))->toBeTrue();
    expect($transaction->fresh()->service_action)->toBe('return/pay');
    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('treats a P190 sub status as pending', function () {
    $this->helpers->createTransaction();

    returnService($this->helpers->pushPayloadFormData(['brq_statuscode' => '190', 'brq_statuscode_detail' => 'P190']))->handleReturnRequest();

    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('leaves a final return to the push', function () {
    $transaction = $this->helpers->createTransaction();

    returnService($this->helpers->pushPayloadFormData(['brq_statuscode' => '190']))->handleReturnRequest();

    expect($transaction->fresh()->service_action)->toBe('pay');
    Event::assertNotDispatched(PayTransactionCompleted::class);
});

it('does not fire again for a payment the push already handled', function () {
    $transaction = $this->helpers->createTransaction(['service_action' => 'push/pay']);

    returnService($this->helpers->pushPayloadFormData(['brq_statuscode' => '791']))->handleReturnRequest();

    expect($transaction->fresh()->service_action)->toBe('push/pay');
    Event::assertNotDispatched(PayTransactionCompleted::class);
});

it('does not fire again for a partial payment the push already stored', function () {
    $this->helpers->createTransaction(['related_transaction_key' => 'GRP1', 'status' => 'paid', 'status_code' => '190']);
    $partial = $this->helpers->pushPayloadFormData([
        'brq_transactions' => 'TX2',
        'brq_relatedtransaction_partialpayment' => 'GRP1',
        'brq_statuscode' => '791',
    ]);

    $this->post('/buckaroo/push', $partial)->assertOk();
    returnService($partial)->handleReturnRequest();

    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('processes any return when forced', function () {
    $this->helpers->createTransaction(['service_action' => 'push/pay']);

    returnService($this->helpers->pushPayloadFormData(['brq_statuscode' => '190']))->forceProcess()->handleReturnRequest();

    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('fails when the transaction is unknown', function () {
    returnService($this->helpers->pushPayloadFormData(['brq_transactions' => 'UNKNOWN']));
})->throws(Exception::class, 'Transaction [UNKNOWN] not found');
