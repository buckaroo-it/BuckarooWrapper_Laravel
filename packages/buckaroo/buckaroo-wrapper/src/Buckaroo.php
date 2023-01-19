<?php

namespace Buckaroo\BuckarooWrapper;

use Buckaroo\BuckarooClient;

class Buckaroo
{
    use DataValidator;

    public function payment(string $payementType, string $methodName, array $data)
    {

        $client = new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));

        $validator = $this->validate($payementType, $methodName, $data);


        if (!$validator) {
            return response()->json('Your Payment Method does not exist', 422);
        }

        if ($validator == 'withOutData') {
            return $client->method($payementType)->$methodName();
        }
        if ($validator->fails()) {
            return $validator->errors();
        }


        $response = $client->method($payementType)->$methodName($validator->validated());

        return $response;
    }
}
