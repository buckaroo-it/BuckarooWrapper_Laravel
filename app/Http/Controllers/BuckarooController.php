<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;

class BuckarooController extends Controller
{
    public function buckaroo(Buckaroo $buckaroo) {

        $payementType = 'creditcard';

        $data = [
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'originalTransactionKey'        => '6C5DBB69E74644958F8C25199514DC6C',
            'name'          => 'mastercard',
            'securityCode'      => '001u8gJNwngKubFCO6FmJod6aESlIFATkKYaj47KlgBp7f3NeVxUzChg1Aug7WD2vc5wut2KU9NPLUaO0tFmzhVLZoDWn7dX4AzGxSjPrsPmDMWYcEkIwMZfcyJqoRfFkF3j15mil3muXxhR1a609NfkTo11J3ENVsvU3k60z'
        ];

        $method = 'payRecurrent';

        return $buckaroo->payment($payementType, $method, $data);
    }
}
