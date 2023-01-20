<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class GiftCard
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string',
            'name' => 'required|string',
            'intersolveCardnumber' => 'required|string',
            'intersolvePIN' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function payRemainder(array $data)
    {
        $validator = Validator::make($data, [
            'originalTransactionKey' => 'required|string',
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'issuer' => 'required|string'
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
            'amountCredit'              => 'required|numeric',
            'invoice'                   => 'required|string',
            'originalTransactionKey'    => 'required|string',
            'name'                      => 'required|string'
        ]);

        return $validator;
    }
}
