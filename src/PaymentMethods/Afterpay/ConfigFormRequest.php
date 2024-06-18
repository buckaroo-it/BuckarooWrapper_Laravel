<?php

namespace Buckaroo\Laravel\PaymentMethods\Afterpay;

use Buckaroo\Laravel\PaymentMethods\DefaultConfigFormRequest;

class ConfigFormRequest extends DefaultConfigFormRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'show_financial_warning' => 'nullable|bool',
            'b2b_min_order_amount' => ['nullable', 'numeric', 'min:0', $this->filled('b2b_max_order_amount') ? 'lt:b2b_max_order_amount' : ''],
            'b2b_max_order_amount' => ['nullable', 'numeric', 'min:0', $this->filled('b2b_min_order_amount') ? 'gt:b2b_min_order_amount' : ''],
        ];
    }

    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'show_financial_warning' => trans('payments.financial_warning.title'),
        ];
    }
}
