<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class EPS
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric'
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
            'amountCredit' => 'required|numeric',
            'invoice' => 'required|string',
            'description' => 'nullable|string',
            'originalTransactionKey' => 'required|string'
        ]);

        return $validator;
    }
}
