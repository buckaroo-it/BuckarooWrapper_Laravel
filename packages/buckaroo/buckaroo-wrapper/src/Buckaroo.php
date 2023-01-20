<?php

namespace Buckaroo\BuckarooWrapper;

use Buckaroo\BuckarooClient;

class Buckaroo
{
    use DataValidator;

    public function payment(string $payementType, string $methodName, array $data)
    {
        $client = $this->createBuckarooClient();
        $validator = $this->validateInput($payementType, $methodName, $data);


        if (is_array($validator)) {
            return $validator;
        }

        if (!$validator) {
            return 'Your Payment Method does not exist';
        }

        if ($validator == 'withOutData') {
            return $client->method($payementType)->$methodName()->toArray();
        }

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $client->method($payementType)->$methodName($validator->validated())->toArray();

    }

    private function createBuckarooClient()
    {
        return new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));
    }

}
