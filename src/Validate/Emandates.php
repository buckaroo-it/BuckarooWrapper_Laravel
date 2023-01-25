<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;

class Emandates
{

    /**
     * @param array $data
     * @return void
     */
    public static function issuerList()
    {
        return 'withOutData';
    }

    /**
     * @param array $data
     * @return void
     */
    public static function createMandate(array $data)
    {
        $validator = Validator::make($data, [
            'emandatereason' => 'required|string',
            'sequencetype' => 'required|numeric',
            'mandateid' => 'required|string',
            'debtorbankid' => 'required|string',
            'debtorreference' => 'required|string',
            'language' => 'required|string',
            'mandateid' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function status(array $data)
    {
        $validator = Validator::make($data, [
            'mandateid' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function modifyMandate(array $data)
    {
        $validator = Validator::make($data, [
            'originalMandateId' => 'required|string',
            'debtorbankid' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function cancelMandate(array $data)
    {
        $validator = Validator::make($data, [
            'mandateid' => 'required|string',
            'emandatereason' => 'required|string',
            'purchaseid' => 'required|string',
        ]);

        return $validator;
    }
}
