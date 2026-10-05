<?php

use Buckaroo\Laravel\Handlers\FormDataParser;

/**
 * An iDEAL push as Buckaroo posts it to the push URL.
 */
function idealPush(array $fields = []): FormDataParser
{
    return new FormDataParser(array_merge([
        'brq_amount' => '10.50',
        'brq_currency' => 'EUR',
        'brq_customer_name' => 'J. de Vries',
        'brq_invoicenumber' => 'INV-1',
        'brq_ordernumber' => 'ORD-1',
        'brq_payment' => 'PAYMENTKEY',
        'brq_payer_hash' => 'PAYERHASH',
        'brq_statuscode' => '190',
        'brq_statuscode_detail' => 'S001',
        'brq_statusmessage' => 'Transaction successfully processed',
        'brq_test' => 'true',
        'brq_transaction_method' => 'ideal',
        'brq_transaction_type' => 'C021',
        'brq_transactions' => 'TX1',
        'brq_SERVICE_ideal_consumerIssuer' => 'ABN AMRO',
        'add_orderid' => '42',
    ], $fields));
}

it('reads the transaction from a push', function () {
    $push = idealPush();

    expect($push->getTransactionKey())->toBe('TX1');
    expect($push->getStatusCode())->toBe(190);
    expect($push->getSubStatusCode())->toBe('S001');
    expect($push->getAmount())->toBe(10.5);
    expect($push->getPaymentMethod())->toBe('ideal');
    expect($push->isSuccess())->toBeTrue();
    expect($push->isRefund())->toBeFalse();
});

it('reads field names in any case', function () {
    $push = new FormDataParser(['BRQ_TRANSACTIONS' => 'TX1', 'Brq_StatusCode' => '791']);

    expect($push->getTransactionKey())->toBe('TX1');
    expect($push->isPendingProcessing())->toBeTrue();
});

it('returns null for a missing or non-numeric amount', function () {
    expect(idealPush(['brq_amount' => 'n/a'])->getAmount())->toBeNull();
    expect(idealPush()->getAmountCredit())->toBeNull();
});

it('detects pending, cancelled, awaiting and approval states', function (array $fields, string $check) {
    expect(idealPush($fields)->{$check}())->toBeTrue();
})->with([
    'pending status' => [['brq_statuscode' => '791'], 'isPendingProcessing'],
    'P190 sub status' => [['brq_statuscode_detail' => 'P190'], 'isPendingProcessing'],
    'P191 sub status' => [['brq_statuscode_detail' => 'P191'], 'isPendingProcessing'],
    'cancelled by user' => [['brq_statuscode' => '890'], 'isCanceled'],
    'cancelled by merchant' => [['brq_statuscode' => '891'], 'isCanceled'],
    'waiting on consumer' => [['brq_statuscode' => '792'], 'isAwaitingConsumer'],
    'pending approval' => [['brq_statuscode' => '794'], 'isPendingApproval'],
]);

it('is not pending once paid', function () {
    expect(idealPush()->isPendingProcessing())->toBeFalse();
});

it('detects a refund push by its parent transaction', function () {
    $push = idealPush(['brq_relatedtransaction_refund' => 'TX0', 'brq_amount_credit' => '5.00']);

    expect($push->isRefund())->toBeTrue();
    expect($push->getRefundParentKey())->toBe('TX0');
    expect($push->getAmountCredit())->toBe(5.0);
});

it('reads service fields of the payment method, or else the primary service', function () {
    expect(idealPush()->getService('consumerIssuer'))->toBe('ABN AMRO');

    $push = new FormDataParser([
        'brq_primary_service' => 'IDEAL',
        'brq_SERVICE_ideal_consumerIssuer' => 'ING',
    ]);

    expect($push->getService('consumerIssuer'))->toBe('ING');
});

it('restores the space that PHP turned into an underscore in Additional Info fields', function () {
    $push = new FormDataParser(['brq_SERVICE_klarna_Additional_Info' => 'note', 'brq_transaction_method' => 'klarna']);

    expect($push->getService('Additional Info'))->toBe('note');
    expect($push->getOriginalItems())->toBe(['brq_SERVICE_klarna_Additional Info' => 'note', 'brq_transaction_method' => 'klarna']);
});

it('reads additional parameters by name in any case', function () {
    expect(idealPush()->getAdditionalInformation('OrderId'))->toBe('42');
});

it('tells test and live transactions apart', function (string $value, bool $isTest) {
    expect(idealPush(['brq_test' => $value])->isTest())->toBe($isTest);
})->with([
    'test' => ['true', true],
    'test, upper case' => ['TRUE', true],
    'test, numeric' => ['1', true],
    'live' => ['false', false],
    'live, upper case' => ['False', false],
    'live, numeric' => ['0', false],
]);

it('treats a push without a test flag as live', function () {
    expect((new FormDataParser(['brq_transactions' => 'TX1']))->isTest())->toBeFalse();
});

it('detects a redirect', function () {
    $push = idealPush(['brq_redirect_url' => 'https://checkout.buckaroo.nl/html/redirect.ashx?r=ABC']);

    expect($push->hasRedirect())->toBeTrue();
    expect($push->getRedirectUrl())->toBe('https://checkout.buckaroo.nl/html/redirect.ashx?r=ABC');
    expect(idealPush()->hasRedirect())->toBeFalse();
});
