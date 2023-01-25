<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\Resources\Constants\Gender;

class In3
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
            'invoiceDate' => 'required|date',
            'customerType' => 'required|string',
            'email' => 'required|email',
            'phone.mobile' => 'required|string',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.quantity' => 'required|numeric|between:0,999999',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
            'company.companyName' => 'required|string',
            'company.chamberOfCommerce' => 'required|string',
            'customer.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'customer.initials' => 'required|string',
            'customer.lastName' => 'required|string',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string',
            'customer.culture' => 'required|string',
            'customer.birthDate' => 'required|date',
            'address.street' => 'required|string',
            'address.houseNumber' => 'required|string',
            'address.houseNumberAdditional' => 'string',
            'address.zipcode' => 'required|string',
            'address.city' => 'required|string',
            'address.country' => 'required|string',
            'subtotals.*.name' => 'required',
            'subtotals.*.value' => 'required'
        ]);

        return $validator;
    }
    /**
     * @param array $data
     * @return void
     */
    public static function payInInstallments(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'order' => 'required|string',
            'invoice' => 'required|string',
            'description' => 'required|string',
            'invoiceDate' => 'required|date',
            'customerType' => 'required|string',
            'email' => 'required|email',
            'phone.mobile' => 'required|string',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.quantity' => 'required|numeric|between:0,999999',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
            'company.companyName' => 'required|string',
            'company.chamberOfCommerce' => 'required|string',
            'customer.gender' => 'required',
            'customer.initials' => 'required|string',
            'customer.lastName' => 'required|string',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string',
            'customer.culture' => 'required|string',
            'customer.birthDate' => 'required|date',
            'address.street' => 'required|string',
            'address.houseNumber' => 'required|string',
            'address.houseNumberAdditional' => 'string',
            'address.zipcode' => 'required|string',
            'address.city' => 'required|string',
            'address.country' => 'required|string',
            'subtotals.*.name' => 'required',
            'subtotals.*.value' => 'required'
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
