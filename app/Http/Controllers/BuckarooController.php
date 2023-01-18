<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;
use Buckaroo\Resources\Constants\Gender;

class BuckarooController extends Controller
{
    public function buckaroo(Buckaroo $buckaroo)
    {

        $payementType = 'afterpaydigiaccept';

        $data =  [
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX',
            'amountDebit'                   => 40.50,
            'order'                         => uniqid(),
            'invoice'                       => uniqid(),
            'b2b'                           => true,
            'addressesDiffer'               => true,
            'customerIPAddress'             => '0.0.0.0',
            'shippingCosts'                 => 0.5,
            'costCentre'                    => 'Test',
            'department'                    => 'Test',
            'establishmentNumber'           => '123456',
            'billing'       => [
                'recipient'        => [
                    'gender'                => Gender::FEMALE,
                    'initials'               => 'AB',
                    'lastName'              => 'Do',
                    'birthDate'             => '1990-01-01',
                    'culture'               => 'NL'
                ],
                'address'       => [
                    'street'                => 'Hoofdstraat',
                    'houseNumber'           => '13',
                    'houseNumberAdditional' => 'a',
                    'zipcode'               => '1234AB',
                    'city'                  => 'Heerenveen',
                    'country'               => 'NL'
                ],
                'phone'         => [
                    'mobile'        => '0698765433'
                ],
                'email'         => 'test@buckaroo.nl'
            ],
            'shipping'      => [
                'recipient'        => [
                    'culture'               => 'NL',
                    'gender'                => Gender::MALE,
                    'initials'              => 'YJ',
                    'lastName'              => 'Jansen',
                    'companyName'           => 'Buckaroo B.V.',
                    'birthDate'             => '1990-01-01',
                    'chamberOfCommerce'     => '12345678',
                    'vatNumber'              => 'NL12345678',
                ],
                'address'       => [
                    'street'                => 'Kalverstraat',
                    'houseNumber'           => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode'               => '4321EB',
                    'city'                  => 'Amsterdam',
                    'country'               => 'NL'
                ],
                'phone'         => [
                    'mobile'        => '0698765433'
                ],
                'email'         => 'test@buckaroo.nl',
            ],
            'articles'      => [
                [
                    'identifier' => uniqid(),
                    'description' => 'Blue Toy Car',
                    'price' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1'
                ],
                [
                    'identifier' => uniqid(),
                    'description' => 'Red Toy Car',
                    'price' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1'
                ],
            ]
        ];


        $method = 'pay';

        $response = $buckaroo->payment($payementType, $method, $data);

        return response()->json([$response]);
    }
}
