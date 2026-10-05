<?php

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([PayTransactionCompleted::class, RefundTransactionCompleted::class]));

it('tells Buckaroo the push arrived so it stops retrying', function (array $stored, array $push) {
    $this->helpers->createTransaction($stored);

    $this->post('/buckaroo/push', $this->helpers->pushPayloadFormData($push))
        ->assertOk()
        ->assertExactJson(['status' => true]);
})->with([
    'processed push' => [[], []],
    'stale push that is ignored' => [['status' => 'paid', 'status_code' => '190'], ['brq_statuscode' => '791']],
    'refund push' => [
        ['transaction_key' => 'RF1', 'related_transaction_key' => 'TX1', 'service_action' => 'refund'],
        ['brq_transactions' => 'RF1', 'brq_relatedtransaction_refund' => 'TX1', 'brq_amount_credit' => '10.00'],
    ],
]);
