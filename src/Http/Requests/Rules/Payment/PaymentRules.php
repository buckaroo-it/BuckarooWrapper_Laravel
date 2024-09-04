<?php

namespace Buckaroo\Laravel\Http\Requests\Rules\Payment;

class PaymentRules implements PaymentRulesInterface
{
    protected array $rules = array();

    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    public function getRules(): array
    {
        return $this->rules;
    }
}
