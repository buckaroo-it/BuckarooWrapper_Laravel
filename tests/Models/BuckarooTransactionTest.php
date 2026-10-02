<?php

use Buckaroo\Laravel\Handlers\FormDataParser;
use Buckaroo\Laravel\Handlers\PaymentGatewayHandler;
use Buckaroo\Laravel\Models\BuckarooTransaction;

function keysOf($transactions): array
{
    return collect($transactions)->pluck('transaction_key')->all();
}

it('finds the transaction of a push by its key first', function () {
    createTransaction(['transaction_key' => 'OTHER', 'related_transaction_key' => 'GRP1']);
    createTransaction(['transaction_key' => 'TX1', 'related_transaction_key' => 'GRP1']);

    $found = BuckarooTransaction::fromResponse(new FormDataParser([
        'brq_transactions' => 'TX1',
        'brq_relatedtransaction_partialpayment' => 'GRP1',
    ]))->get();

    expect(keysOf($found))->toBe(['TX1', 'OTHER']);
});

it('finds the earlier partial payments of a group', function () {
    createTransaction(['transaction_key' => 'TX1', 'related_transaction_key' => 'GRP1']);
    createTransaction(['transaction_key' => 'UNRELATED']);

    $found = BuckarooTransaction::fromResponse(new FormDataParser([
        'brq_transactions' => 'TX2',
        'brq_relatedtransaction_partialpayment' => 'GRP1',
    ]))->get();

    expect(keysOf($found))->toBe(['TX1']);
});

it('finds the parent of a refund', function () {
    createTransaction(['transaction_key' => 'TX1']);

    $found = BuckarooTransaction::fromResponse(new FormDataParser([
        'brq_transactions' => 'RF1',
        'brq_relatedtransaction_refund' => 'TX1',
    ]))->get();

    expect(keysOf($found))->toBe(['TX1']);
});

it('finds nothing for an unknown transaction', function () {
    createTransaction();

    expect(BuckarooTransaction::fromResponse(new FormDataParser(['brq_transactions' => 'UNKNOWN']))->exists())->toBeFalse();
});

it('lists only paid transactions as completed, optionally per action', function () {
    createTransaction(['transaction_key' => 'PAID', 'status' => 'paid', 'status_code' => '190', 'service_action' => 'push/pay']);
    createTransaction(['transaction_key' => 'REFUNDED', 'status' => 'paid', 'status_code' => '190', 'service_action' => 'refund']);
    createTransaction(['transaction_key' => 'PENDING', 'status' => 'pending', 'status_code' => '791']);
    createTransaction(['transaction_key' => 'FAILED', 'status' => 'failed', 'status_code' => '490']);

    expect(keysOf(BuckarooTransaction::completed()->orderBy('id')->get()))->toBe(['PAID', 'REFUNDED']);
    expect(keysOf(BuckarooTransaction::completed('pay')->get()))->toBe(['PAID']);
    expect(keysOf(BuckarooTransaction::completed('refund')->get()))->toBe(['REFUNDED']);
});

it('links refunds to the payment they refund', function () {
    $payment = createTransaction(['transaction_key' => 'TX1']);
    $refund = createTransaction(['transaction_key' => 'RF1', 'related_transaction_key' => 'TX1', 'service_action' => 'refund']);
    createTransaction(['transaction_key' => 'TX2', 'related_transaction_key' => 'TX1', 'service_action' => 'pay']);

    expect(keysOf($payment->refunds))->toBe(['RF1']);
    expect($refund->relatedTransaction->is($payment))->toBeTrue();
});

it('keeps each step of the service action once', function (string $action, string $stored) {
    expect(createTransaction(['service_action' => $action])->service_action)->toBe($stored);
})->with([
    'plain' => ['pay', 'pay'],
    'push after pay' => ['push/pay', 'push/pay'],
    'repeated push' => ['push/push/pay', 'push/pay'],
    'return after push' => ['return/push/pay', 'return/push/pay'],
]);

it('tells push and return updates apart', function () {
    $pushed = createTransaction(['service_action' => 'push/pay']);
    $returned = createTransaction(['service_action' => 'return/pay']);

    expect($pushed->isPushAction())->toBeTrue();
    expect($pushed->isReturnAction())->toBeFalse();
    expect($returned->isReturnAction())->toBeTrue();
    expect($returned->isPushAction())->toBeFalse();
});

it('gives the gateway handler of its payment method', function () {
    $gateway = createTransaction(['payment_method' => 'ideal'])->getPaymentGateway();

    expect($gateway)->toBeInstanceOf(PaymentGatewayHandler::class);
    expect($gateway->getServiceCode())->toBe('ideal');
});
