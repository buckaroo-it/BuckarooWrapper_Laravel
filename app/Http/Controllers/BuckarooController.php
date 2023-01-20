<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;
use Buckaroo\Resources\Constants\Gender;

class BuckarooController extends Controller
{
    public function buckaroo(Buckaroo $buckaroo)
    {

        $payementType = 'payperemail';

        $method = 'paymentInvitation';

        $data = [
            'amountDebit'           => 10,
            'invoice'               => 'testinvoice 123',
            'merchantSendsEmail'    => false,
            'email'                 => 'johnsmith@gmail.com',
            'expirationDate'        => '2030-01-01',
            'paymentMethodsAllowed' => 'ideal,mastercard,paypal',
            'attachment'            => '',
            'customer'              => [
                'gender'        => Gender::FEMALE,
                'firstName'     => 'John',
                'lastName'      => 'Smith'
            ]
        ];

        $response = $buckaroo->payment($payementType, $method, $data);

        return response()->json($response);
    }
}
