<?php

namespace Buckaroo\BuckarooWrapper;
use Buckaroo\BuckarooClient;

class Buckaroo
{
    use ValidateData;

    public function payment(string $payementType,string $method,array $data){

        $client = new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));

        $validator = $this->validateMethod($payementType, $method , $data);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 404);
        }

        $response = $client->method($payementType)->$method($validator->validated());

        return  response()->json($response);
    }
}
