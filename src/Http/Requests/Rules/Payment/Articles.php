<?php

namespace Buckaroo\Laravel\Http\Requests\Rules\Payment;

class Articles extends PaymentRuleDecorator
{
    public function __construct(PaymentRulesInterface $decorator)
    {
        $this->decorator = $decorator;
    }

    public function getRules(): array
    {
        $this->rules = array(
            'payment.articles'                  => 'required|array|min:1',
            'payment.articles.*.identifier'     => 'required',
            'payment.articles.*.description'    => 'required',
            'payment.articles.*.vatPercentage'  => 'required',
            'payment.articles.*.quantity'       => 'required',
            'payment.articles.*.price'          => 'required',
        );

        return parent::getRules($this->rules);
    }
}
