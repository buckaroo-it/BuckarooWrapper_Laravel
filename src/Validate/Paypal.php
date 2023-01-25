<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\BuckarooClient;

class Paypal
{
    private static function buckarooClient()
    {
        return new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));
    }
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit' =>  'required|numeric|between:0,999999.99',
            'invoice' => 'required',
        ]);

        return $validator;
    }
    /**
     * @param array $data
     * @return void
     */
    public static function payRecurrent(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit'   => 'required|numeric|between:0,999999.99',
            'originalTransactionKey' => 'required|string',
            'invoice' => 'required',
        ]);

        return $validator;
    }
    /**
     * @param array $data
     * @return void
     */
    public static function extraInfo(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,999999.99',
            'invoice' => 'required|string',
            'customer.name' => 'required|string',
            'address.street' => 'required|string',
            'address.street2' => 'nullable|string',
            'address.city' => 'required|string',
            'address.state' => 'required|string',
            'address.zipcode' => 'required|string',
            'address.country' => 'required|string',
            'phone.mobile' => 'required|string',
        ]);

        return $validator;
    }
    /**
     * @param array $data
     * @return void
     */
    public static function refund(array $data)
    {
        $validator = Validator::make($data, [
        ]);

        return $validator;
    }
    /**
     * @param array $data
     * @return void
     */
    public static function createCombined(array $data)
    {
        $client = self::buckarooClient();

        $combined = Validator::make($data, [
            'includeTransaction' => 'required|boolean',
            'transactionVatPercentage' => 'required|numeric|between:0,999999',
            'configurationCode' => 'required|string',
            'email' => 'required|email',
            'rate_plans.add.*' => 'required',
            'rate_plans.add.startDate' => 'required|date',
            'rate_plans.add.ratePlanCode' => 'required|string',
            'phone.mobile' => 'required|string',
            'debtor.code' => 'required|string',
            'person.firstName' => 'required|string',
            'person.lastName' => 'required|string',
            'person.gender' => 'required',
            'person.culture' => 'nullable|string',
            'person.birthDate' => 'nullable|date',
            'address.street' => 'nullable|string',
            'address.houseNumber' => 'nullable|string',
            'address.zipcode' => 'nullable|string',
            'address.city' => 'nullable|string',
            'address.country' => 'required|string'
        ]);

        $subscriptions = $client->method('subscriptions')->manually()->createCombined($combined->valid());

        $extraInfo = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,999999.99',
            'invoice' => 'required|string',
            'customer.name' => 'required|string',
            'address.street' => 'nullable|string',
            'address.street2' => 'nullable|string',
            'address.city' => 'nullable|string',
            'address.state' => 'nullable|string',
            'address.zipcode' => 'nullable|string',
            'address.country' => 'nullable|string',
            'phone.mobile' => 'nullable|string',
        ]);

        $paypal_extra_info = $client->method('paypal')->manually()->extraInfo($extraInfo->valid());

        $pay = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,999999.99',
            'invoice' =>'required|string',
        ]);

        if ($combined->fails()) {
            return $combined;
        }
        if ($extraInfo->fails()) {
            return $extraInfo;
        }
        if ($pay->fails()) {
            return $pay;
        }
        $response = $client->method('paypal')->combine([$subscriptions, $paypal_extra_info])->pay($pay->validated());

        return $response->toArray();
    }
}
