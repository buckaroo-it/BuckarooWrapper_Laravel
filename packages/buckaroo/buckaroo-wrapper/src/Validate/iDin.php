<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class iDin
{
    /**
     * @param array $data
     * @return void
     */
    public static function identify(array $data)
    {
        $validator = Validator::make($data, [
            'issuer' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function verify(array $data)
    {
        $validator = Validator::make($data, [
            'issuer' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function login(array $data)
    {
        $validator = Validator::make($data, [
            'issuer' => 'required|string'
        ]);

        return $validator;
    }
}
