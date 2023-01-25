<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class Payconiq
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'description' => 'required|string',
            'invoice' => 'required|string',
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
