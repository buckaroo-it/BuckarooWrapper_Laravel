<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\BuckarooClient;

class Marketplaces
{

    private static function buckarooClient()
    {
        return new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));
    }

    /**
     * @test
     */
    public static function splitPay(array $data)
    {
        $client = self::buckarooClient();

        $split = Validator::make($data, [
            'daysUntilTransfer' => 'required|integer|between:0,999999.99',
            'marketplace.amount' => 'required|numeric|between:0,999999.99',
            'marketplace.description' => 'required|string',
            'sellers.*.accountId' => 'required|string',
            'sellers.*.amount' => 'required|numeric|between:0,999999.99',
            'sellers.*.description' => 'required|string',
        ]);

        $pay = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,999999.99',
            'issuer' => 'nullable|string',

        ]);
        if ($split->fails()) {
            return $split;
        }
        if ($pay->fails()) {
            return $pay;
        }
        $marketplace = $client->method('marketplaces')->manually()->split($split->validated());
        $response = $client->method('ideal')->combine($marketplace)->pay($pay->validated());

        return $response->toArray();
    }

    /**
     * @test
     */
    public static function transfer(array $data)
    {
        $validator = Validator::make($data, [
            'originalTransactionKey' => 'required|string',
            'marketplace.amount' => 'required|numeric|between:0,999999.99',
            'marketplace.description' => 'required|string',
            'sellers.*.accountId' => 'required|string',
            'sellers.*.amount' => 'required|numeric|between:0,999999.99',
            'sellers.*.description' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @test
     */
    public static function refundSupplementary(array $data)
    {
        $client = self::buckarooClient();

        $refundSupplementary = Validator::make($data, [
            'sellers.*.accountId' => 'required|string',
            'sellers.*.description' => 'required|string',

        ]);

        $refund = Validator::make($data, [
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'amountCredit' => 'required|numeric|between:0,999999.99',
        ]);

        if ($refundSupplementary->fails()) {
            return $refundSupplementary;
        }

        if ($refund->fails()) {
            return $refund;
        }

        $marketplace = $client->method('marketplaces')->manually()->refundSupplementary($refundSupplementary->validated());

        $response = $client->method('ideal')->combine($marketplace)->refund($refund->validated());

        return $response->toArray();
    }
}
