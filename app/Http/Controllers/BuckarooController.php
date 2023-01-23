<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;
use Buckaroo\Resources\Constants\Gender;
use Buckaroo\Resources\Constants\CreditManagementInstallmentInterval;

class BuckarooController extends Controller
{
    public function buckaroo(Buckaroo $buckaroo)
    {

        $payementType = 'subscriptions';

        $method = 'createCombined';

        $data = [
            'includeTransaction'        => false,
            'transactionVatPercentage'  => 5,
            'configurationCode'         => 'xxxxx',
            'email'                     => 'test@buckaroo.nl',
            'rate_plans'        => [
                'add'        => [
                    'startDate'         => '2022-01-01',
                    'ratePlanCode'      => 'xxxxxx',
                ]
            ],
            'phone'                     => [
                'mobile'                => '0612345678'
            ],
            'debtor'                    => [
                'code'          => 'xxxxxx'
            ],
            'person'                    => [
                'firstName'         => 'John',
                'lastName'          => 'Do',
                'gender'            => Gender::FEMALE,
                'culture'           => 'nl-NL',
                'birthDate'         => '1990-01-01'
            ],
            'address'           => [
                'street'        => 'Hoofdstraat',
                'houseNumber'   => '90',
                'zipcode'       => '8441ER',
                'city'          => 'Heerenveen',
                'country'       => 'NL'
            ],
            'invoice'       => uniqid(),
            'amountDebit' => 10.10,
            'issuer' => 'ABNANL2A'
        ];

        $response = $buckaroo->payment($payementType, $method, $data);

        return response()->json($response);
    }
}
