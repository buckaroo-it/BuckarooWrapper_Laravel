<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class CreditClick
{

    /**
     * @param array $data
     */

    public static function pay(array $data){
        //Validate PayRecurrent

        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'string',
            'email' => 'required|email',
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string'
        ]);
        return $validator;
    }

    /**
     * @param array $data
     */
    public static function refund(array $data){
        //Validate PayRecurrent
        
        $validator = Validator::make($data, [
            'amountCredit' => 'required|numeric',
            'invoice'       => 'string',
            'description'   => 'required|string',
            'originalTransactionKey' => 'required|string',
            'serviceParameters.reason' => 'required|string'
        ]);

        return $validator;
    }
}
