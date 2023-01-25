<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\Resources\Constants\Gender;

class BankTransfer
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric',
            'email' => 'required|email',
            'country' => 'required|string',
            'dateDue' => 'required|date',
            'sendMail' => 'required|boolean',
            'customer.gender' =>   'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string'
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
