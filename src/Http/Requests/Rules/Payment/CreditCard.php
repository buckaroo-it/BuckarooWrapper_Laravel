<?php

namespace Buckaroo\Laravel\Http\Requests\Rules\Payment;

class CreditCard extends PaymentRuleDecorator
{
    public function __construct(PaymentRulesInterface $decorator)
    {
        $this->decorator = $decorator;
    }

    public function getRules(): array
    {
        $this->rules = array(
            'payment.creditcard.name'                 => 'required'
        );

        return parent::getRules($this->rules);
    }
}
