<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class iDealQR
{

    /**
     * @param array $data
     * @return void
     */
    public static function generate(array $data)
    {
        $validator = Validator::make($data, [
            'description' => 'required|string',
            'minAmount' => 'required|numeric|between:0,99999999.99',
            'maxAmount' => 'required|numeric|between:0,99999999.99',
            'imageSize' => 'required|numeric|between:0,5000',
            'purchaseId' => 'required|string',
            'isOneOff' => 'required|boolean',
            'amount' => 'required|numeric|between:0,99999999.99',
            'amountIsChangeable' => 'required|boolean',
            'expiration' => 'required|date|after:today',
            'isProcessing' => 'required|boolean'
        ]);

        return  $validator;
    }
}
