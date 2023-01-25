<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\Resources\Constants\Gender;

class KlarnaKP
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
            'reservationNumber' => 'required|string',
            'serviceParameters.articles.*.identifier' => 'required|string',
            'serviceParameters.articles.*.quantity' => 'required|numeric|between:0,999999',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function reserve(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'operatingCountry' => 'required|string',
            'pno' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^(0[1-9]|[12][0-9]|3[01])(0[1-9]|1[012])([0-9]{4})$/', $value)) {
                        $fail($attribute . ' is not a valid date.');
                    }
                },
            ],
            'billing.recipient.firstName' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'billing.phone.mobile' => 'required|string',
            'billing.email' => 'required|email',
            'shipping.recipient.firstName' => 'required|string',
            'shipping.recipient.lastName' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
            'shipping.email' => 'required|email',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.vatPercentage' => 'required|numeric|between:0,99.99',
            'articles.*.quantity' => 'required|numeric|between:0,999999',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function cancelReserve(array $data)
    {
        $validator = Validator::make($data, [
            'reservationNumber' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function updateReserve(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'billing.recipient.careOf' => 'required|string',
            'billing.recipient.firstName' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'billing.phone.mobile' => 'required|string',
            'billing.phone.landLine' => 'required|string',
            'billing.email' => 'required|email',
            'shipping.recipient.careOf' => 'required|string',
            'shipping.recipient.firstName' => 'required|string',
            'shipping.recipient.lastName' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
            'shipping.email' => 'required|email',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.vatPercentage' => 'required|numeric|between:0,100',
            'articles.*.quantity' => 'required|numeric|between:0,999999',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
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
            'originalTransactionKey' => 'required|string',
        ]);

        return $validator;
    }
}
