<?php

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Illuminate\Support\Facades\Event;

beforeEach(fn () => Event::fake([PayTransactionCompleted::class]));

it('answers a posted return with the transaction', function () {
    $this->helpers->createTransaction(['order' => 'ORD-1']);

    $this->post('/buckaroo/return', $this->helpers->pushPayloadFormData(['brq_statuscode' => '791']))
        ->assertOk()
        ->assertJson(['transaction_key' => 'TX1', 'order' => 'ORD-1', 'service_action' => 'return/pay']);

    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});

it('accepts a return sent as a GET redirect', function () {
    $this->helpers->createTransaction();

    $this->get('/buckaroo/return?' . http_build_query($this->helpers->pushPayloadFormData(['brq_statuscode' => '791'])))
        ->assertOk()
        ->assertJson(['transaction_key' => 'TX1']);

    Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
});
