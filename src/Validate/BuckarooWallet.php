<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;

class BuckarooWallet
{
    /**
     * @param array $data
     * @return void
     */
    public static function createWallet(array $data)
    {
        //Validate Create Wallet
        $validator = Validator::make($data, [
            'walletId' => 'required|numeric',
            'email' => 'required|email',
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string',
            'bankAccount.iban' => 'required|string|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function updateWallet(array $data)
    {
        //Validate Update Wallet
        $validator = Validator::make($data, [
            'walletId' => 'required|numeric',
            'email' => 'required|email',
            'status' => 'required|string',
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string',
            'bankAccount.iban' => 'required|string|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function getInfo(array $data)
    {
        //Validate Get Info
        $validator = Validator::make($data, [
            'walletId' => 'required|numeric',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function release(array $data)
    {
        //Validate Release
        $validator = Validator::make($data, [
            'amountCredit' => 'required|numeric',
            'walletId' => 'required|numeric',
            'walletMutationGuid' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function deposit(array $data)
    {
        //Validate Deposit
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'amountCredit' => 'required|numeric',
            'walletId' => 'required|numeric',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function reserve(array $data)
    {
        //Validate Reserve
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'amountCredit' => 'required|numeric',
            'walletId' => 'required|numeric',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function withdrawal(array $data)
    {
        //Validate Withdrawal
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'amountDebit' => 'required|numeric',
            'walletId' => 'required|numeric',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function cancel(array $data)
    {
        //Validate Cancel
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'amountDebit' => 'required|numeric',
            'walletMutationGuid' => 'required|string',
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
            'invoice' => 'required|string',
            'description' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'walletId' => 'required|numeric'
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
            'originalTransactionKey' => 'required|string',
        ]);

        return $validator;
    }
}
