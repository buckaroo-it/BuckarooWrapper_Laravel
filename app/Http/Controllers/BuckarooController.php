<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;
use Buckaroo\Resources\Constants\Gender;

class BuckarooController extends Controller
{
    public function buckaroo(Buckaroo $buckaroo)
    {

        $payementType = 'giftcard';

        $method = 'refund';

        $data = [
            'amountCredit'              => 10,
            'invoice'                   => 'testinvoice 123',
            'originalTransactionKey'    => '2D04704995B74D679AACC59F87XXXXXX',
            'name'                      => 'boekenbon'
        ];


        $response = $buckaroo->payment($payementType, $method, $data);

        return response()->json([$response->toArray()]);
    }
}
