<?php

namespace Buckaroo\Laravel;

class Buckaroo extends BaseService
{
    use DataValidator;

    public function payment(string $payementType, string $methodName, array $data = null)
    {
        $validator = $this->validateInput($payementType, $methodName, $data);

        try {
            if (!$validator) {
                return response('Your Payment Method does not exist', 422);
            } elseif (is_array($validator)) {
                return $validator;
            } elseif ($validator == 'withOutData') {
                return $this->client->method($payementType)->$methodName();
            } elseif ($validator->fails()) {
                return $validator->errors();
            } else {
                return $this->client->method($payementType)->$methodName($validator->validated());
            }
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
