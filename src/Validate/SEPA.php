<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class SEPA
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'iban' => 'required|string|regex:/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/',
            'bic' => 'required|string',
            'collectdate' => 'required|date',
            'mandateReference' => 'required|string',
            'mandateDate' => 'required|date',
            'customer.name' => 'required|string',
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

    /**
     * @param array $data
     * @return void
     */
    public static function authorize(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'iban' => 'required|string|regex:/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/',
            'bic' => 'required|string',
            'collectdate' => 'required|date',
            'mandateReference' => 'required|string',
            'mandateDate' => 'required|date',
            'customer.name' => 'required|string',
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
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'originalTransactionKey' => 'required|string',
            'invoice' => 'required|string',
            'collectdate' => 'required|date',
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
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'invoice' => 'required|string',
            'iban' => 'required|string|regex:/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/',
            'bic' => 'required|string',
            'contractID' => 'required|string',
            'mandateDate' => 'required|date',
            'customerReferencePartyName' => 'required|string',
            'customer.name' => 'required|string',
            'address.street' => 'required|string',
            'address.houseNumber' => 'required|string',
            'address.houseNumberAdditional' => 'nullable|string',
            'address.zipcode' => 'required|string',
            'address.city' => 'required|string',
            'address.country' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function payWithEmandate(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'invoice' => 'required|string',
            'mandateReference' => 'required|string',
        ]);

        return $validator;
    }
}
