<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class Belfius
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        //Validate Pay
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string'
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
            'amountCredit' => 'required|numeric',
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string'
        ]);

        return $validator;
    }
}
