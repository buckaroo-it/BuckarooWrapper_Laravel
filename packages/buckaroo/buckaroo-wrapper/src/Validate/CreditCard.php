<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class CreditCard
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
            'invoice' => 'string',
            'name' => 'required|string'
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
            'amountDebit' => 'required|numeric',
            'invoice' => 'string',
            'name' => 'required|string',
            'encryptedCardData' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function payWithSecurityCode(array $data)
    {
        //Validate Pay With Security Code
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'string',
            'originalTransactionKey' => 'required|string',
            'name' => 'required|string',
            'encryptedSecurityCode' => 'required|string'
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
            'invoice' => 'string',
            'originalTransactionKey' => 'required|string',
            'name' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function authorize(array $data)
    {
        //Validate Authorize
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'string',
            'name' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function authorizeEncrypted(array $data)
    {
        //Validate Card Authorize Encrypted
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'string',
            'name' => 'required|string',
            'encryptedCardData' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function authorizeWithSecurityCode(array $data)
    {
        //Validate Authorize With Security Code
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'name' => 'required|string',
            'encryptedSecurityCode' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function capture(array $data)
    {
        //Validate Capture
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'name' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     */
    public static function cancelAuthorize(array $data)
    {
        //Validate Cancel Authorize
        $validator = Validator::make($data, [
            'amountCredit' => 'required|numeric',
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'name' => 'required|string'
        ]);

        return $validator;
    }
}
