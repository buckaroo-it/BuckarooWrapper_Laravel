<?php

namespace Buckaroo\Laravel\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\Resources\Constants\Gender;

class PayPerEmail
{


    /**
     * @param array $data
     * @return void
     */
    public static function paymentInvitation(array $data)
    {
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string',
            'merchantSendsEmail' => 'required|boolean',
            'email' => 'required|email',
            'expirationDate' => 'required|date',
            'paymentMethodsAllowed' => 'required|string',
            'attachment' => 'nullable|file',
            'customer.gender' =>  'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string',
            'attachments.*.name' => 'nullable|string',
        ]);

        return $validator;
    }
}
