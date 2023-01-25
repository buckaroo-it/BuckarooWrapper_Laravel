<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;

class Afterpay
{

    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        //Validate Pay
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'order' => 'required|string',
            'invoice' => 'required|string',
            'clientIP' => 'nullable|string',
            'billing.recipient.category' => 'required|string',
            'billing.recipient.careOf' => 'required|string',
            'billing.recipient.title' => 'required|string',
            'billing.recipient.firstName' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.recipient.birthDate' => 'required|date',
            'billing.recipient.conversationLanguage' => 'required|string',
            'billing.recipient.identificationNumber' => 'required|string',
            'billing.recipient.customerNumber' => 'required|string',
            'billing.address.street' => 'nullable|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'nullable|string',
            'billing.address.zipcode' => 'nullable|string',
            'billing.address.city' => 'nullable|string',
            'billing.address.phone.mobile' => 'nullable|string',
            'billing.address.phone.landline' => 'nullable|string',
            'billing.address.country' => 'nullable|string',
            'billing.address.email' => 'nullable|email',
            'shipping.recipient.category' => 'nullable|string',
            'shipping.recipient.careOf' => 'nullable|string',
            'shipping.recipient.companyName' => 'nullable|string',
            'shipping.recipient.firstName' => 'nullable|string',
            'shipping.recipient.lastName' => 'nullable|string',
            'shipping.recipient.chamberOfCommerce' => 'nullable|string',
            'shipping.address.street' => 'nullable|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'nullable|string',
            'shipping.address.zipcode' => 'nullable|string',
            'shipping.address.city' => 'nullable|string',
            'shipping.address.country' => 'nullable|string',
            'articles.*.identifier' => 'nullable|string',
            'articles.*.description' => 'nullable|string',
            'articles.*.vatPercentage' => 'nullable|numeric',
            'articles.*.quantity' => 'nullable|integer',
            'articles.*.price' => 'nullable|numeric',

        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function authorize(array $data)
    {

        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'order' => 'required|string',
            'invoice' => 'required|string',
            'clientIP' => 'nullable|string',
            'billing.recipient.category' => 'required|string',
            'billing.recipient.careOf' => 'required|string',
            'billing.recipient.title' => 'required|string',
            'billing.recipient.firstName' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.recipient.birthDate' => 'required|date',
            'billing.recipient.conversationLanguage' => 'required|string',
            'billing.recipient.identificationNumber' => 'required|string',
            'billing.recipient.customerNumber' => 'required|string',
            'billing.address.street' => 'nullable|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'nullable|string',
            'billing.address.zipcode' => 'nullable|string',
            'billing.address.city' => 'nullable|string',
            'billing.address.phone.mobile' => 'nullable|string',
            'billing.address.phone.landline' => 'nullable|string',
            'billing.address.country' => 'nullable|string',
            'billing.address.email' => 'nullable|email',
            'shipping.recipient.category' => 'nullable|string',
            'shipping.recipient.careOf' => 'nullable|string',
            'shipping.recipient.companyName' => 'nullable|string',
            'shipping.recipient.firstName' => 'nullable|string',
            'shipping.recipient.lastName' => 'nullable|string',
            'shipping.recipient.chamberOfCommerce' => 'nullable|string',
            'shipping.address.street' => 'nullable|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'nullable|string',
            'shipping.address.zipcode' => 'nullable|string',
            'shipping.address.city' => 'nullable|string',
            'shipping.address.country' => 'nullable|string',
            'articles.*.identifier' => 'nullable|string',
            'articles.*.description' => 'nullable|string',
            'articles.*.vatPercentage' => 'nullable|numeric',
            'articles.*.quantity' => 'nullable|integer',
            'articles.*.price' => 'nullable|numeric',

        ]);

        return $validator;
    }
    /**
     * @param array $data
     * @return void
     */
    public static function capture(array $data)
    {

        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'order' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'invoice' => 'required|string',
            'clientIP' => 'nullable|string',
            'billing.recipient.category' => 'required|string',
            'billing.recipient.careOf' => 'required|string',
            'billing.recipient.title' => 'required|string',
            'billing.recipient.firstName' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.recipient.birthDate' => 'required|date',
            'billing.recipient.conversationLanguage' => 'required|string',
            'billing.recipient.identificationNumber' => 'required|string',
            'billing.recipient.customerNumber' => 'required|string',
            'billing.address.street' => 'nullable|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'nullable|string',
            'billing.address.zipcode' => 'nullable|string',
            'billing.address.city' => 'nullable|string',
            'billing.address.phone.mobile' => 'nullable|string',
            'billing.address.phone.landline' => 'nullable|string',
            'billing.address.country' => 'nullable|string',
            'billing.address.email' => 'nullable|email',
            'shipping.recipient.category' => 'nullable|string',
            'shipping.recipient.careOf' => 'nullable|string',
            'shipping.recipient.companyName' => 'nullable|string',
            'shipping.recipient.firstName' => 'nullable|string',
            'shipping.recipient.lastName' => 'nullable|string',
            'shipping.recipient.chamberOfCommerce' => 'nullable|string',
            'shipping.address.street' => 'nullable|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'nullable|string',
            'shipping.address.zipcode' => 'nullable|string',
            'shipping.address.city' => 'nullable|string',
            'shipping.address.country' => 'nullable|string',
            'articles.*.identifier' => 'nullable|string',
            'articles.*.description' => 'nullable|string',
            'articles.*.vatPercentage' => 'nullable|numeric',
            'articles.*.quantity' => 'nullable|integer',
            'articles.*.price' => 'nullable|numeric',

        ]);

        return $validator;
    }
    /**
     * @param array $data
     * @return void
     */
    public static function refund(array $data)
    {
        //Validate Capture
        $validator = Validator::make($data, [
            'originalTransactionKey' => 'required|string',
            'invoice' => 'required|string',
            'amountCredit' => 'required|numeric|between:0,99999999.99',
            'clientIP' => 'nullable|string',

            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.vatPercentage' => 'required|numeric',
            'articles.*.quantity' => 'required|integer',
            'articles.*.price' => 'required|numeric'
        ]);

        return $validator;
    }
}
