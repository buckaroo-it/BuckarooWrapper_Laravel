<?php

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\PaymentProcessing\ReturnService;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([PayTransactionCompleted::class]));

function returnService(array $fields): ReturnService
{
    $request = ReplyHandlerRequest::create('/buckaroo/return', 'POST', signForm($fields));

    return ReturnService::make($request);
}

it('completes a pending payment on return when no push arrived yet', function () {
    $transaction = createTransaction(['service_action' => 'pay']);

    $result = returnService(pushFields(['brq_statuscode' => '791']))->handleReturnRequest();

    expect($result->is($transaction))->toBeTrue();
    expect($transaction->fresh()->service_action)->toBe('return/pay');
    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('treats a P190 sub status as pending', function () {
    createTransaction();

    returnService(pushFields(['brq_statuscode' => '190', 'brq_statuscode_detail' => 'P190']))->handleReturnRequest();

    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('leaves a final return to the push', function () {
    $transaction = createTransaction();

    returnService(pushFields(['brq_statuscode' => '190']))->handleReturnRequest();

    expect($transaction->fresh()->service_action)->toBe('pay');
    Event::assertNotDispatched(PayTransactionCompleted::class);
});

it('does not fire again for a payment the push already handled', function () {
    $transaction = createTransaction(['service_action' => 'push/pay']);

    returnService(pushFields(['brq_statuscode' => '791']))->handleReturnRequest();

    expect($transaction->fresh()->service_action)->toBe('push/pay');
    Event::assertNotDispatched(PayTransactionCompleted::class);
});

it('does not fire again for a partial payment the push already stored', function () {
    createTransaction(['related_transaction_key' => 'GRP1', 'status' => 'paid', 'status_code' => '190']);
    $partial = pushFields([
        'brq_transactions' => 'TX2',
        'brq_relatedtransaction_partialpayment' => 'GRP1',
        'brq_statuscode' => '791',
    ]);

    $this->post('/buckaroo/push', signForm($partial))->assertOk();
    returnService($partial)->handleReturnRequest();

    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('processes any return when forced', function () {
    createTransaction(['service_action' => 'push/pay']);

    returnService(pushFields(['brq_statuscode' => '190']))->forceProcess()->handleReturnRequest();

    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('fails when the transaction is unknown', function () {
    returnService(pushFields(['brq_transactions' => 'UNKNOWN']));
})->throws(Exception::class, 'Transaction [UNKNOWN] not found');
