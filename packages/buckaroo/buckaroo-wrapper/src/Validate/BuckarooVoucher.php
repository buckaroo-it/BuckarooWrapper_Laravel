<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class BuckarooVoucher
{
    /**
     * @param array $data
     * @return void
     */
    public static function create(array $data)
    {
        //Validate Create
        $validator = Validator::make($data, [
            'usageType' => 'required|numeric',
            'validFrom' => 'required|date',
            'validUntil' => 'required|date',
            'creationBalance' => 'required|numeric'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        //Validate Pay
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string',
            'vouchercode' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function payRemainder(array $data)
    {
        //Validate PayRemainder
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string',
            'vouchercode' => 'required|string',
            'originalTransaction' => 'required|string'
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

    /**
     * @param array $data
     * @return void
     */
    public static function getBalance(array $data)
    {
        //Validate GetBalance
        $validator = Validator::make($data, [
            'vouchercode' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function deactivate(array $data)
    {
        //Validate Deactivate
        $validator = Validator::make($data, [
            'vouchercode' => 'required|string',
        ]);

        return $validator;
    }
}
