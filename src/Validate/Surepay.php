<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class Surepay
{
    /**
     * @param array $data
     * @return void
     */
    public static function verify(array $data)
    {
        $validator = Validator::make($data, [
            'bankAccount.iban' => 'required|string|regex:/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/',
            'bankAccount.accountName' => 'required|string',
        ]);

        return $validator;
    }
}
