<?php

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([PayTransactionCompleted::class, RefundTransactionCompleted::class]));

/**
 * Push fields that turn the default push into one for refund `RF1` of `TX1`.
 */
function refundFields(array $fields = []): array
{
    return array_merge([
        'brq_transactions' => 'RF1',
        'brq_relatedtransaction_refund' => 'TX1',
        'brq_amount_credit' => '10.00',
    ], $fields);
}

it('does not move a paid transaction back to pending', function () {
    $transaction = $this->helpers->createTransaction(['status' => 'paid', 'status_code' => '190']);

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData(['brq_statuscode' => '791']))
        ->assertOk();

    expect($transaction->fresh()->status)->toBe('paid');
    expect($transaction->fresh()->status_code)->toBe('190');
    Event::assertNotDispatched(PayTransactionCompleted::class);
});

it('completes a pending transaction on a paid push', function () {
    $transaction = $this->helpers->createTransaction();

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData())
        ->assertOk();

    $transaction->refresh();
    expect($transaction->status)->toBe('paid');
    expect($transaction->status_code)->toBe('190');
    expect($transaction->status_subcode_description)->toBe('Transaction successfully processed');
    expect($transaction->service_action)->toBe('push/pay');
    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('processes a duplicate paid push every time', function () {
    $transaction = $this->helpers->createTransaction();
    $payload = $this->helpers->pushPayloadFormData();

    $this->post('/buckaroo/push', $payload)->assertOk();
    $this->post('/buckaroo/push', $payload)->assertOk();

    expect($transaction->fresh()->status)->toBe('paid');
    Event::assertDispatchedTimes(PayTransactionCompleted::class, 2);
});

it('lets a final status change to another final status', function () {
    $transaction = $this->helpers->createTransaction(['status' => 'failed', 'status_code' => '490']);

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData())->assertOk();

    expect($transaction->fresh()->status)->toBe('paid');
    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('does not fire the pay event when the customer cancelled', function () {
    $transaction = $this->helpers->createTransaction();

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData(['brq_statuscode' => '890']))->assertOk();

    expect($transaction->fresh()->status)->toBe('cancelled');
    Event::assertNotDispatched(PayTransactionCompleted::class);
});

it('does not move a paid refund back to pending', function () {
    $this->helpers->createTransaction(['status' => 'paid', 'status_code' => '190']);
    $refund = $this->helpers->createTransaction([
        'transaction_key' => 'RF1',
        'related_transaction_key' => 'TX1',
        'status' => 'paid',
        'status_code' => '190',
        'service_action' => 'refund',
    ]);

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData(refundFields(['brq_statuscode' => '791'])))
        ->assertOk();

    expect($refund->fresh()->status)->toBe('paid');
    Event::assertNotDispatched(RefundTransactionCompleted::class);
});

it('completes a pending refund on a paid refund push', function () {
    $this->helpers->createTransaction(['status' => 'paid', 'status_code' => '190']);
    $refund = $this->helpers->createTransaction([
        'transaction_key' => 'RF1',
        'related_transaction_key' => 'TX1',
        'service_action' => 'refund',
    ]);

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData(refundFields()))->assertOk();

    expect($refund->fresh()->status)->toBe('paid');
    expect((float) $refund->fresh()->amount)->toBe(-10.0);
    Event::assertDispatchedTimes(RefundTransactionCompleted::class, 1);
});

it('updates a refund on a pending refund push without firing the refund event', function () {
    $this->helpers->createTransaction(['status' => 'paid', 'status_code' => '190']);
    $refund = $this->helpers->createTransaction([
        'transaction_key' => 'RF1',
        'related_transaction_key' => 'TX1',
        'status' => 'open',
        'service_action' => 'refund',
    ]);

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData(refundFields(['brq_statuscode' => '791'])))->assertOk();

    expect($refund->fresh()->status)->toBe('pending');
    Event::assertNotDispatched(RefundTransactionCompleted::class);
});

it('stores a new partial payment even when the first payment is paid', function () {
    $this->helpers->createTransaction([
        'related_transaction_key' => 'GRP1',
        'status' => 'paid',
        'status_code' => '190',
        'order' => 'ORD-1',
    ]);

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData([
        'brq_transactions' => 'TX2',
        'brq_relatedtransaction_partialpayment' => 'GRP1',
        'brq_statuscode' => '791',
    ]))->assertOk();

    $this->assertDatabaseHas('buckaroo_transactions', [
        'transaction_key' => 'TX2',
        'related_transaction_key' => 'GRP1',
        'status' => 'pending',
        'order' => 'ORD-1',
        'service_action' => 'push/pay',
    ]);
    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('does not store the same partial payment twice', function () {
    $this->helpers->createTransaction(['related_transaction_key' => 'GRP1', 'status' => 'paid', 'status_code' => '190']);
    $this->helpers->createTransaction(['transaction_key' => 'TX2', 'related_transaction_key' => 'GRP1', 'status' => 'pending']);

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData([
        'brq_transactions' => 'TX2',
        'brq_relatedtransaction_partialpayment' => 'GRP1',
    ]))->assertOk();

    $this->assertDatabaseCount('buckaroo_transactions', 2);
    $this->assertDatabaseHas('buckaroo_transactions', ['transaction_key' => 'TX2', 'status' => 'paid']);
});

it('fails when the transaction is unknown', function () {
    $this->withoutExceptionHandling();

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData(['brq_transactions' => 'UNKNOWN']));
})->throws(Exception::class, 'Transaction [UNKNOWN] not found');
