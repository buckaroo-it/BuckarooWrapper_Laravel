<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class CreditClick
{

    /**
     * @param array $data
     */

    public static function pay(array $data)
    {
        //Validate Pay
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string',
            'email' => 'required|email',
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string'
        ]);
        return $validator;
    }

    /**
     * @param array $data
     */
    public static function refund(array $data)
    {
        //Validate Refund
        $validator = Validator::make($data, [
            'amountCredit' => 'required|numeric',
            'invoice' => 'required|string',
            'description' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'refundreason' => 'required|string'
        ]);

        return $validator;
    }
}
