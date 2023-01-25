<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class CreditClick
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
            'invoice' => 'required|string',
            'email' => 'required|email',
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string'
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
            'amountCredit' => 'required|numeric|between:0,99999999.99',
            'invoice' => 'required|string',
            'description' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'refundreason' => 'required|string'
        ]);

        return $validator;
    }
}
