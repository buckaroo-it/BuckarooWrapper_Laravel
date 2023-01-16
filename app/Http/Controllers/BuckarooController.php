<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;

class BuckarooController extends Controller
{
    public function buckaroo(Buckaroo $buckaroo) {
        $payementType = 'creditcard';
        $data = [
            'amountDebit'   => 22.44,
            'invoice'       => uniqid(),
            'name'          => 'visa'
        ];
        $method = 'pay';

        return $buckaroo->payment($payementType, $method, $data);
    }
}
