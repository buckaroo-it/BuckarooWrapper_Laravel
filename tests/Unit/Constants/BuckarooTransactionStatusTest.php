<?php

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;

it('maps a Buckaroo status code to a transaction status', function (string $code, string $status) {
    expect(BuckarooTransactionStatus::fromTransactionStatus($code))->toBe($status);
})->with([
    'success' => ['190', 'paid'],
    'authorize accepted' => ['I013', 'open'],
    'group transaction' => ['I150', 'open'],
    'waiting on user input' => ['790', 'pending'],
    'pending processing' => ['791', 'pending'],
    'waiting on consumer' => ['792', 'pending'],
    'on hold' => ['793', 'pending'],
    'pending approval' => ['794', 'pending'],
    'authorize cancelled' => ['I014', 'cancelled'],
    'cancelled by user' => ['890', 'cancelled'],
    'cancelled by merchant' => ['891', 'cancelled'],
    'failed' => ['490', 'failed'],
    'validation failure' => ['491', 'failed'],
    'technical error' => ['492', 'failed'],
    'rejected' => ['690', 'failed'],
    'unknown code' => ['999', 'failed'],
]);

it('treats paid, failed and cancelled as final', function (string $status, bool $final) {
    expect(BuckarooTransactionStatus::isFinal($status))->toBe($final);
})->with([
    'paid' => ['paid', true],
    'failed' => ['failed', true],
    'cancelled' => ['cancelled', true],
    'open' => ['open', false],
    'pending' => ['pending', false],
]);
