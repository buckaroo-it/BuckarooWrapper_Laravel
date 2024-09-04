<?php

namespace Buckaroo\Laravel\Http\Requests\Rules\Payment;

class Issuer extends PaymentRuleDecorator
{
    public function __construct(PaymentRulesInterface $decorator)
    {
        $this->decorator = $decorator;
    }

    public function getRules(): array
    {
        $this->rules = array(
            'payment.issuer.id' => 'required'
        );

        return parent::getRules($this->rules);
    }
}
