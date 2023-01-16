<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;

class BuckarooController extends Controller
{
    public function buckaroo(Buckaroo $buckaroo) {
        $payementType = 'creditcard';
        $method = 'pay';
        $data = [
            'amountDebit'   => 'asdasd',
            'invoice'       => uniqid(),
            'name'          => 'visa'
        ];


        return $buckaroo->payment($payementType, $method, $data);
    }
}
