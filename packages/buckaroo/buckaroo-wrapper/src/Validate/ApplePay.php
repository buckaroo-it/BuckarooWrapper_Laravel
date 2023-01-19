<?php

namespace Buckaroo\BuckarooWrapper\Validate;

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
            'amountDebit' => 'required|numeric',
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
            'amountCredit' => 'required|numeric',
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
        ]);

        return $validator;
    }
}
