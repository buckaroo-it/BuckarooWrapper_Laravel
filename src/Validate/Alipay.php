<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class Alipay
{

    /**
     * @return void
     * @param array $data
     */
    public static function pay(array $data)
    {
        //Validate Pay
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'invoice' => 'required|string',
            'useMobileView' => 'required|boolean',
        ]);

        return $validator;
    }

    /**
     * @return void
     * @param array $data
     */
    public static function refund(array $data)
    {
        //Validate Pay Redirect
        $validator = Validator::make($data, [
            'amountCredit' => 'required|numeric|between:0,99999999.99',
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string'
        ]);

        return $validator;
    }
}
