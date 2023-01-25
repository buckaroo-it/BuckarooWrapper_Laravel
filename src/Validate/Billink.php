<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;

class Billink
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        //Validate Pay
        $validator = Validator::make($data, [
            'amountDebit' => ['required', 'numeric', 'between:0,99999999.99'],
            'order' => ['required', 'string'],
            'invoice' => ['required', 'string'],
            'trackAndTrace' => ['required', 'string'],
            'vATNumber' => ['required', 'string'],
            'billing.recipient.category' => ['required', 'string'],
            'billing.recipient.careOf' => ['required', 'string'],
            'billing.recipient.title' => ['required', 'string'],
            'billing.recipient.initials' => ['required', 'string'],
            'billing.recipient.firstName' => ['required', 'string'],
            'billing.recipient.lastName' => ['required', 'string'],
            'billing.recipient.birthDate' => ['required', 'date'],
            'billing.recipient.chamberOfCommerce' => ['required', 'string'],
            'billing.address.street' => ['required', 'string'],
            'billing.address.houseNumber' => ['required', 'string'],
            'billing.address.houseNumberAdditional' => ['string'],
            'billing.address.zipcode' => ['required', 'string'],
            'billing.address.city' => ['required', 'string'],
            'billing.address.country' => ['required', 'string'],
            'billing.phone.mobile' => ['required', 'string'],
            'billing.phone.landline' => ['required', 'string'],
            'billing.email' => ['required', 'string', 'email'],
            'shipping.recipient.category' => ['required','string'],
            'shipping.recipient.careOf' => ['required', 'string'],
            'shipping.recipient.title' => ['required', 'string'],
            'shipping.recipient.initials' => ['required', 'string'],
            'shipping.recipient.firstName' => ['required', 'string'],
            'shipping.recipient.lastName' => ['required', 'string'],
            'shipping.recipient.birthDate' => ['required', 'date'],
            'shipping.address.street' => ['required', 'string'],
            'shipping.address.houseNumber' => ['required', 'string'],
            'shipping.address.houseNumberAdditional' => ['string'],
            'shipping.address.zipcode' => ['required', 'string'],
            'shipping.address.city' => ['required', 'string'],
            'shipping.address.country' => ['required', 'string'],
            'articles.*.identifier' => ['required', 'string'],
            'articles.*.description' => ['required', 'string'],
            'articles.*.vatPercentage' => ['required', 'numeric'],
            'articles.*.quantity' => ['required', 'numeric'],
            'articles.*.price' => ['required', 'numeric'],
            'articles.*.priceExcl' => ['required', 'numeric']
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function authorize(array $data)
    {
        //Validate Authorize
        $validator = Validator::make($data, [
            'amountDebit' => ['required', 'numeric', 'between:0,99999999.99'],
            'order' => ['required', 'string'],
            'invoice' => ['required', 'string'],
            'trackAndTrace' => ['required', 'string'],
            'vATNumber' => ['required', 'string'],
            'billing.recipient.category' => ['required', 'string'],
            'billing.recipient.careOf' => ['required', 'string'],
            'billing.recipient.title' => ['required', 'string'],
            'billing.recipient.initials' => ['required', 'string'],
            'billing.recipient.firstName' => ['required', 'string'],
            'billing.recipient.lastName' => ['required', 'string'],
            'billing.recipient.birthDate' => ['required', 'date'],
            'billing.recipient.chamberOfCommerce' => ['required', 'string'],
            'billing.address.street' => ['required', 'string'],
            'billing.address.houseNumber' => ['required', 'string'],
            'billing.address.houseNumberAdditional' => ['string'],
            'billing.address.zipcode' => ['required', 'string'],
            'billing.address.city' => ['required', 'string'],
            'billing.address.country' => ['required', 'string'],
            'billing.phone.mobile' => ['required', 'string'],
            'billing.phone.landline' => ['required', 'string'],
            'billing.email' => ['required', 'string', 'email'],
            'shipping.recipient.category' => ['required','string'],
            'shipping.recipient.careOf' => ['required', 'string'],
            'shipping.recipient.title' => ['required', 'string'],
            'shipping.recipient.initials' => ['required', 'string'],
            'shipping.recipient.firstName' => ['required', 'string'],
            'shipping.recipient.lastName' => ['required', 'string'],
            'shipping.recipient.birthDate' => ['required', 'date'],
            'shipping.address.street' => ['required', 'string'],
            'shipping.address.houseNumber' => ['required', 'string'],
            'shipping.address.houseNumberAdditional' => ['string'],
            'shipping.address.zipcode' => ['required', 'string'],
            'shipping.address.city' => ['required', 'string'],
            'shipping.address.country' => ['required', 'string'],
            'articles.*.identifier' => ['required', 'string'],
            'articles.*.description' => ['required', 'string'],
            'articles.*.vatPercentage' => ['required', 'numeric'],
            'articles.*.quantity' => ['required', 'numeric'],
            'articles.*.price' => ['required', 'numeric'],
            'articles.*.priceExcl' => ['required', 'numeric']
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function capture(array $data)
    {
        //Validate Capture
        $validator = Validator::make($data, [
            'originalTransactionKey' => ['required', 'string'],
            'invoice' => ['required', 'string'],
            'amountDebit' => ['required', 'numeric', 'between:0,99999999.99'],
            'articles.*.identifier' => ['required', 'string'],
            'articles.*.description' => ['required', 'string'],
            'articles.*.vatPercentage' => ['required', 'numeric'],
            'articles.*.quantity' => ['required', 'numeric'],
            'articles.*.price' => ['required', 'numeric'],
            'articles.*.priceExcl' => ['required', 'numeric'],
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function refund(array $data)
    {
        //Validate Refund
        $validator = Validator::make($data, [
            'amountCredit' => ['required', 'numeric', 'between:0,99999999.99'],
            'invoice' => ['required', 'string'],
            'originalTransactionKey' => ['required', 'string']
        ]);

        return $validator;
    }
}
