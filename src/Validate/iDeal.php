<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;

class iDeal
{
    /**
     * @param array $data
     * @return void
     */
    public static function pay(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'issuer' => 'required|string',
            'pushURL' => 'required|string',
            'returnURL' => 'required|string',
            'clientIP.address' => 'required|string',
            'clientIP.type' => 'required',
            'additionalParameters.initiated_by_magento' => 'required|string',
            'additionalParameters.service_action' => 'required|string'
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
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
            'amountCredit' => 'required|numeric|between:0,99999999.99',
            'clientIP.address' => 'required|string',
            'clientIP.type' => 'required',
            'additionalParameters.initiated_by_magento' => 'required|string',
            'additionalParameters.service_action' => 'required|string'
        ]);

        return $validator;
    }
}
