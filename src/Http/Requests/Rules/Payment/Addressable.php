<?php

namespace Buckaroo\Laravel\Http\Requests\Rules\Payment;

class Addressable extends PaymentRuleDecorator
{
    protected string $rootNode;

    public function __construct(PaymentRulesInterface $decorator, string $rootNode)
    {
        $this->decorator = $decorator;
        $this->rootNode = $rootNode;
    }

    public function getRules(): array
    {
        $this->rules = array(
            'payment.' . $this->rootNode . '.recipient.category' => 'required',
            'payment.' . $this->rootNode . '.recipient.careOf' => 'required',
            'payment.' . $this->rootNode . '.recipient.title' => 'required',
            'payment.' . $this->rootNode . '.recipient.firstName' => 'required',
            'payment.' . $this->rootNode . '.recipient.lastName' => 'required',
            'payment.' . $this->rootNode . '.recipient.birthDate' => 'required',
        );

        return parent::getRules($this->rules);
    }
}
