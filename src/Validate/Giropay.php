<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class Giropay
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'bic' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,99999999.99'
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
            'amountCredit'              => 'required|numeric|between:0,99999999.99',
            'invoice'                   => 'required|string',
            'description'               => 'nullable|string',
            'originalTransactionKey'    => 'required|string'
        ]);

        return $validator;
    }
}
