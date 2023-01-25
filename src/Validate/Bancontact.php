<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;

class Bancontact
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
            'saveToken' => 'required|boolean'
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
            'originalTransactionKey' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function payEncrypted(array $data)
    {
        //Validate Pay Encrypted
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'description' => 'required|string',
            'encryptedCardData' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @return void
     * @test
     */
    public static function payRecurrent(array $data)
    {
        //Validate PayRecurrent
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric',
            'originalTransactionKey' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @return void
     * @test
     */
    public static function authenticate(array $data)
    {
        //Validate Authenticate
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric',
            'description' => 'required|string',
            'savetoken' => 'required|string'
        ]);

        return $validator;
    }
}
