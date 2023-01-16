<?php

namespace Buckaroo\BuckarooWrapper;
use Buckaroo\BuckarooClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Buckaroo
{
    public function payment($payementType, $method, $data){

        $client = new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));

        $validator = Validator::make($data, [
            'amountDebit'   =>  'required|decimal:2',
            'invoice'       => 'string',
            'name'          =>  'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $response = $client->method($payementType)->$method($validator->validated());

        return $response->getStatusCode();
    }
}
