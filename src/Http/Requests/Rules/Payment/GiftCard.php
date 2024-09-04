<?php

namespace Buckaroo\Laravel\Http\Requests\Rules\Payment;

class GiftCard extends PaymentRuleDecorator
{
    public function __construct(PaymentRulesInterface $decorator)
    {
        $this->decorator = $decorator;
    }

    public function getRules(): array
    {
        $this->rules = array(
            'payment.giftcard.name'                 => 'required',
            'payment.giftcard.intersolveCardnumber' => 'required',
            'payment.giftcard.intersolvePIN'        => 'required',
        );

        return parent::getRules($this->rules);
    }
}
