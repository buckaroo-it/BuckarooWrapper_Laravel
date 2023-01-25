<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\BuckarooClient;

class Subscriptions
{
    private static function buckarooClient()
    {
        return new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));
    }

    /**
     * @param array $data
     * @return void
     */
    public static function create(array $data)
    {
        $validator = Validator::make($data, [
            'rate_plans.add.*.startDate' => 'required|date',
            'rate_plans.add.*.ratePlanCode' => 'required|string',
            'configurationCode' => 'required|string',
            'debtor.code' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function update(array $data)
    {
        $validator = Validator::make($data, [
            'subscriptionGuid' => 'required|string',
            'configurationCode' => 'required|string',
            'rate_plans.update.*' => 'required',
            'rate_plans.update.ratePlanGuid' => 'required|string',
            'rate_plans.update.startDate' => 'required|date',
            'rate_plans.update.endDate' => 'required|date',
            'rate_plans.update.charge.*' => 'required',
            'rate_plans.update.charge.ratePlanChargeGuid' => 'required|string',
            'rate_plans.update.charge.baseNumberOfUnits' => 'required|numeric|between:0,999999',
            'rate_plans.update.charge.pricePerUnit' => 'required|numeric|between:0,999999.99',
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

        $createCombined = Validator::make($data, [
            'includeTransaction' => 'required|boolean',
            'transactionVatPercentage' => 'required|numeric|between:0,999999.99',
            'configurationCode' => 'required|string',
            'email' => 'required|email',
            'rate_plans.*.add.*.startDate' => 'required|date|after:today',
            'rate_plans.*.add.*.ratePlanCode' => 'required|string',
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
        $pay = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,999999.99',
            'issuer' => 'required|string'
        ]);

        if ($createCombined->fails()) {
            return $createCombined;
        }

        if ($pay->fails()) {
            return $pay;
        }

        $subscriptions = $client->method('subscriptions')->manually()->createCombined($createCombined->validated());
        $response = $client->method('ideal')->combine($subscriptions)->pay($pay->validated());

        return $response->toArray();
    }

    /**
     * @param array $data
     * @return void
     */
    public static function updateCombined(array $data)
    {
        $client = self::buckarooClient();

        $updateCombined = Validator::make($data, [
            'startRecurrent' => 'required|boolean',
            'subscriptionGuid' => 'required|string',
        ]);

        $pay = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,999999.99',
            'issuer' => 'required|string'
        ]);

        if ($updateCombined->fails()) {
            return $updateCombined;
        }

        if ($pay->fails()) {
            return $pay;
        }
        $subscription = $client->method('subscriptions')->manually()->updateCombined($updateCombined->validated());

        $response = $client->method('ideal')->combine($subscription)->pay($pay->validated());


        return $response->toArray();
    }

    /**
     * @param array $data
     * @return void
     */
    public static function stop(array $data)
    {
        $validator = Validator::make($data, [
            'subscriptionGuid' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function info(array $data)
    {
        $validator = Validator::make($data, [
            'subscriptionGuid' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function deletePaymentConfig(array $data)
    {
        $validator = Validator::make($data, [
            'subscriptionGuid' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function pause(array $data)
    {
        $validator = Validator::make($data, [
            'resumeDate' => 'required|date',
            'subscriptionGuid' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function resume(array $data)
    {
        $validator = Validator::make($data, [
            'resumeDate' => 'required|date',
            'subscriptionGuid' => 'required|string',
        ]);

        return $validator;
    }
}
