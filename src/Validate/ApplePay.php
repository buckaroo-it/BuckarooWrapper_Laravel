<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;

class ApplePay
{

    /**
     * @return void
     * @param array $data
     */
    public static function payRedirect(array $data)
    {
        //Validate Pay Redirect
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'invoice' => 'required|string',
            'servicesSelectableByClient' => 'required|string',
            'continueOnIncomplete' => 'required|string',
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
            'originalTransactionKey' => 'required|string',
        ]);

        return $validator;
    }
}
