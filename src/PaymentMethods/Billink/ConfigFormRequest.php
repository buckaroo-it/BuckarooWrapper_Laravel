<?php

namespace Buckaroo\Laravel\PaymentMethods\Billink;

use Buckaroo\Laravel\PaymentMethods\DefaultConfigFormRequest;

class ConfigFormRequest extends DefaultConfigFormRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'show_financial_warning' => 'nullable|bool',
            'customer_type' => 'nullable|in:both,b2b,b2c',
            'b2b_min_order_amount' => ['nullable', 'numeric', 'min:0', $this->filled('b2b_max_order_amount') ? 'lt:b2b_max_order_amount' : ''],
            'b2b_max_order_amount' => ['nullable', 'numeric', 'min:0', $this->filled('b2b_min_order_amount') ? 'gt:b2b_min_order_amount' : ''],
        ];
    }

    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'show_financial_warning' => trans('payments.financial_warning.title'),
            'customer_type' => trans('payments.billink.customer_type'),
            'b2b_min_order_amount' => trans('payments.b2b.min_amount'),
            'b2b_max_order_amount' => trans('payments.b2b.max_amount'),
        ];
    }
}
