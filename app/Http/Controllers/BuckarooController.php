<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;

class BuckarooController extends Controller
{
    public function buckaroo(Request $request)
    {
        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('creditcard', 'pay', [
            'name'          => 'visa',
            'amountDebit'   => 10.25,
            'invoice'       => uniqid()
        ]);

        return response()->json($response->toArray());
    }
}
