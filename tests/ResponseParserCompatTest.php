<?php

namespace Buckaroo\Laravel\Tests;

use Buckaroo\Laravel\Handlers\FormDataParser;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\ResponseParser;

class ResponseParserCompatTest extends TestCase
{
    public function test_make_routes_brq_payloads_to_the_form_data_parser()
    {
        $parser = ResponseParser::make(['brq_statuscode' => '190', 'brq_amount' => '10.00']);

        $this->assertInstanceOf(FormDataParser::class, $parser);
    }

    public function test_make_routes_json_payloads_to_the_json_parser()
    {
        $parser = ResponseParser::make(['Transaction' => ['Status' => ['Code' => ['Code' => 190]]]]);

        $this->assertInstanceOf(JsonParser::class, $parser);
    }

    public function test_make_stays_compatible_with_the_collection_signature()
    {
        $parser = ResponseParser::make(['brq_statuscode' => '190'], 'ignored', 'args');

        $this->assertInstanceOf(FormDataParser::class, $parser);
        $this->assertSame(['brq_statuscode' => '190'], $parser->getOriginalItems());
    }
}
