<?php

use Buckaroo\Laravel\Handlers\JsonParser;

/**
 * The redirect response Buckaroo documents for a pay request
 * (docs.buckaroo.io, Bancontact requests, "Example response").
 */
function redirectResponse(array $fields = []): array
{
    return array_merge([
        'Key' => '0EF39AA94BD64FF38F1540DEB6A3C1D2',
        'Status' => [
            'Code' => ['Code' => 790, 'Description' => 'Pending input'],
            'SubCode' => null,
            'DateTime' => '2017-03-30T12:50:36',
        ],
        'RequiredAction' => [
            'RedirectURL' => 'https://testcheckout.buckaroo.nl/html/redirect.ashx?r=77C66A69FF4240DBB497CA73E5B4E8F0',
            'RequestedInformation' => null,
            'PayRemainderDetails' => null,
            'Name' => 'Redirect',
            'TypeDeprecated' => 0,
        ],
        'Services' => null,
        'CustomParameters' => null,
        'AdditionalParameters' => null,
        'RequestErrors' => null,
        'Invoice' => 'testinvoice 123',
        'ServiceCode' => null,
        'IsTest' => true,
        'Currency' => 'EUR',
        'AmountDebit' => 10,
        'TransactionType' => null,
        'MutationType' => 0,
        'RelatedTransactions' => null,
        'ConsumerMessage' => null,
        'Order' => null,
        'CustomerName' => null,
        'PayerHash' => null,
        'PaymentKey' => null,
    ], $fields);
}

function paidIdealResponse(array $fields = []): array
{
    return redirectResponse(array_merge([
        'Status' => [
            'Code' => ['Code' => 190, 'Description' => 'Success'],
            'SubCode' => ['Code' => 'S001', 'Description' => 'Transaction successfully processed'],
        ],
        'RequiredAction' => null,
        'ServiceCode' => 'ideal',
        'Services' => [
            [
                'Name' => 'ideal',
                'Action' => null,
                'Parameters' => [
                    ['Name' => 'consumerIssuer', 'Value' => 'ABN AMRO'],
                    ['Name' => 'consumerName', 'Value' => 'J. de Vries'],
                ],
            ],
        ],
    ], $fields));
}

it('reads the redirect a pay request needs', function () {
    $response = new JsonParser(redirectResponse());

    expect($response->hasRedirect())->toBeTrue();
    expect($response->getRedirectUrl())->toBe('https://testcheckout.buckaroo.nl/html/redirect.ashx?r=77C66A69FF4240DBB497CA73E5B4E8F0');
    expect($response->getStatusCode())->toBe(790);
    expect($response->isPendingProcessing())->toBeTrue();
    expect($response->isSuccess())->toBeFalse();
});

it('has no redirect once the payment is processed', function () {
    expect((new JsonParser(paidIdealResponse()))->hasRedirect())->toBeFalse();
});

it('reads a processed payment', function () {
    $response = new JsonParser(paidIdealResponse());

    expect($response->getTransactionKey())->toBe('0EF39AA94BD64FF38F1540DEB6A3C1D2');
    expect($response->isSuccess())->toBeTrue();
    expect($response->getSubStatusCode())->toBe('S001');
    expect($response->getSubCodeMessage())->toBe('Transaction successfully processed');
    expect($response->getPaymentMethod())->toBe('ideal');
    expect($response->getPrimaryService())->toBe('ideal');
    expect($response->isTest())->toBeTrue();
});

it('falls back to the debit amount when there is no amount', function () {
    expect((new JsonParser(redirectResponse()))->getAmount())->toBe(10.0);
    expect((new JsonParser(redirectResponse(['Amount' => 7.5])))->getAmount())->toBe(7.5);
});

it('unwraps a push body', function (string $wrapper) {
    $push = new JsonParser([$wrapper => paidIdealResponse()]);

    expect($push->getTransactionKey())->toBe('0EF39AA94BD64FF38F1540DEB6A3C1D2');
    expect($push->getStatusCode())->toBe(190);
})->with(['transaction push' => ['Transaction'], 'data request push' => ['DataRequest']]);

it('reads service parameters by name', function () {
    $response = new JsonParser(paidIdealResponse());

    expect($response->getServiceParameter('ideal', 'consumerIssuer'))->toBe('ABN AMRO');
    expect($response->getServiceParameter('ideal', 'unknown'))->toBeNull();
    expect($response->getServiceParameters('creditcard'))->toBeNull();
});

it('reads service parameters whatever the key casing', function () {
    $response = new JsonParser(paidIdealResponse([
        'Services' => [['Name' => 'ideal', 'parameters' => [['Name' => 'consumerIssuer', 'Value' => 'ING']]]],
    ]));

    expect($response->getServiceParameter('ideal', 'consumerIssuer'))->toBe('ING');
});

it('finds the related transaction of a refund', function () {
    $response = new JsonParser(paidIdealResponse([
        'AmountCredit' => 5,
        'RelatedTransactions' => [['RelationType' => 'refund', 'RelatedTransactionKey' => 'PARENT']],
    ]));

    expect($response->isRefund())->toBeTrue();
    expect($response->getRefundParentKey())->toBe('PARENT');
    expect($response->getRelatedTransactionPartialPayment())->toBeNull();
    expect($response->getAmountCredit())->toBe(5.0);
});

it('finds the group transaction of a partial payment', function () {
    $response = new JsonParser(paidIdealResponse([
        'RelatedTransactions' => [['RelationType' => 'partialpayment', 'RelatedTransactionKey' => 'GROUP']],
    ]));

    expect($response->isRefund())->toBeFalse();
    expect($response->getRelatedTransactionPartialPayment())->toBe('GROUP');
});

it('reads additional parameters by name', function () {
    $response = new JsonParser(paidIdealResponse([
        'AdditionalParameters' => ['List' => [['Name' => 'orderId', 'Value' => '42']]],
    ]));

    expect($response->getAdditionalInformation('orderId'))->toBe('42');
    expect($response->getAdditionalInformation('missing'))->toBeNull();
});

it('treats a success with a P190 or P191 sub status as still pending', function (string $subCode) {
    $response = new JsonParser(paidIdealResponse([
        'Status' => ['Code' => ['Code' => 190], 'SubCode' => ['Code' => $subCode, 'Description' => 'Pending']],
    ]));

    expect($response->isPendingProcessing())->toBeTrue();
})->with(['P190', 'P191']);

it('is not pending once processed', function () {
    expect((new JsonParser(paidIdealResponse()))->isPendingProcessing())->toBeFalse();
});

it('detects cancelled, awaiting and approval states', function (int $code, string $check) {
    expect((new JsonParser(paidIdealResponse(['Status' => ['Code' => ['Code' => $code]]])))->{$check}())->toBeTrue();
})->with([
    'cancelled by user' => [890, 'isCanceled'],
    'cancelled by merchant' => [891, 'isCanceled'],
    'waiting on consumer' => [792, 'isAwaitingConsumer'],
    'pending approval' => [794, 'isPendingApproval'],
]);
