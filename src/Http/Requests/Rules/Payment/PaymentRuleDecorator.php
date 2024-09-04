<?php

namespace Buckaroo\Laravel\Http\Requests\Rules\Payment;


class PaymentRuleDecorator implements PaymentRulesInterface
{
    protected PaymentRulesInterface $decorator;
    protected array $rules;

    public function __construct(PaymentRulesInterface $decorator)
    {
        $this->decorator = $decorator;
    }

    public function getRules(): array
    {
        return array_merge($this->rules, $this->decorator->getRules());
    }
}
