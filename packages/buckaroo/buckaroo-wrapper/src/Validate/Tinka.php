<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\Resources\Constants\Gender;

class Tinka
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'order' => 'required|string',
            'invoice' => 'required|string',
            'description' => 'required|string',
            'paymentMethod' => 'required|string',
            'deliveryMethod' => 'required|string',
            'deliveryDate' => 'required|date',
            'articles.*.type' => 'required',
            'articles.*.description' => 'required|string',
            'articles.*.brand' => 'required|string',
            'articles.*.manufacturer' => 'required|string',
            'articles.*.color' => 'required|string',
            'articles.*.size' => 'required|string',
            'articles.*.quantity' => 'required|numeric|between:0,99999999.99',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
            'articles.*.unitCode' => 'required|string',
            'customer.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string',
            'customer.initials' => 'required|string',
            'customer.birthDate' => 'required|date',
            'billing.recipient.lastNamePrefix' => 'required|string',
            'billing.email' => 'required|email',
            'billing.phone.mobile' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'nullable|string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'shipping.recipient.lastNamePrefix' => 'required|string',
            'shipping.email' => 'required|email',
            'shipping.phone.mobile' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'nullable|string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
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
            'amountCredit' => 'required|numeric|between:0,99999999.99',
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string'
        ]);

        return $validator;
    }
}
