<?php

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    Event::fake([PayTransactionCompleted::class]);
    createTransaction();
});

it('refuses a callback with a wrong signature', function (string $uri) {
    $payload = array_merge(signForm(pushFields()), ['brq_statuscode' => '490']);

    $this->post($uri, $payload)->assertStatus(400);
})->with('callback routes');

it('reports a refused callback as an invalid signature', function () {
    $this->withoutExceptionHandling();

    $this->post('/buckaroo/push', pushFields());
})->throws(HttpException::class, 'Invalid signature');

it('refuses callbacks while the credentials are not set', function (string $uri) {
    useDefaultPackageConfig();

    $this->post($uri, signForm(pushFields(), ''))->assertStatus(400);
})->with('callback routes');

it('refuses a callback without a signature', function (string $uri) {
    $this->post($uri, pushFields())->assertStatus(400);
})->with('callback routes');

it('refuses a re-split callback before it changes the transaction', function () {
    $original = signForm([
        'brq_amount' => '10.00',
        'brq_currency' => 'EUR',
        'brq_customer_name' => 'Abrq_statuscode=190brq_t=',
        'brq_statuscode' => '490',
        'brq_test' => 'true',
        'brq_transaction_method' => 'ideal',
        'brq_transactions' => 'TX1',
    ]);
    $replayed = array_merge($original, [
        'brq_customer_name' => 'A',
        'brq_statuscode' => '190',
        'brq_t' => 'brq_statuscode=490',
    ]);

    $this->post('/buckaroo/push', $replayed)->assertStatus(400);

    $this->assertDatabaseHas('buckaroo_transactions', ['transaction_key' => 'TX1', 'status' => 'pending']);
    Event::assertNotDispatched(PayTransactionCompleted::class);
});

it('keeps surrounding spaces in signed values', function (string $uri) {
    $this->post($uri, signForm(pushFields(['brq_customer_name' => ' J. de Vries '])))->assertOk();
})->with('callback routes');

it('accepts a callback with an empty signed value', function () {
    $this->post('/buckaroo/push', signForm(pushFields(['brq_customer_name' => ''])))->assertOk();
});

it('accepts a callback with mixed-case field names', function () {
    $payload = signForm([
        'BRQ_TRANSACTIONS' => 'TX1',
        'BRQ_STATUSCODE' => '190',
        'Brq_Amount' => '10.00',
        'brq_currency' => 'EUR',
        'brq_transaction_method' => 'ideal',
        'brq_test' => 'true',
    ]);

    $this->post('/buckaroo/push', $payload)->assertOk();

    $this->assertDatabaseHas('buckaroo_transactions', ['transaction_key' => 'TX1', 'status' => 'paid']);
});
