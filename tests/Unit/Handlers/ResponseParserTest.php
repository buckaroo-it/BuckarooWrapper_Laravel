<?php

use Buckaroo\Laravel\Handlers\FormDataParser;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\ResponseParser;

it('parses payloads with brq_ fields as form data', function (array $payload) {
    expect(ResponseParser::make($payload))->toBeInstanceOf(FormDataParser::class);
})->with([
    'lower case' => [['brq_statuscode' => '190']],
    'mixed case' => [['BRQ_StatusCode' => '190', 'Transaction' => 'x']],
]);

it('parses every other payload as JSON', function (array $payload) {
    expect(ResponseParser::make($payload))->toBeInstanceOf(JsonParser::class);
})->with([
    'transaction' => [['Transaction' => ['Status' => ['Code' => ['Code' => 190]]]]],
    'empty' => [[]],
]);

it('keeps the original payload and ignores extra collection arguments', function () {
    $parser = ResponseParser::make(['brq_statuscode' => '190'], 'ignored', 'args');

    expect($parser->getOriginalItems())->toBe(['brq_statuscode' => '190']);
});
