<?php

namespace Buckaroo\Laravel\PaymentMethods;

use Buckaroo\Laravel\Constants\PaymentMethodMode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DefaultConfigFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'frontend_label' => ['nullable', 'string'],
            'mode' => ['required', Rule::in(PaymentMethodMode::getAllModes())],
            'countries' => ['nullable', 'array'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0', $this->filled('max_order_amount') ? 'lt:max_order_amount' : ''],
            'max_order_amount' => ['nullable', 'numeric', 'min:0', $this->filled('min_order_amount') ? 'gt:min_order_amount' : ''],
        ];
    }

    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'frontend_label' => trans('payments.front_label'),
            'mode' => trans('payments.mode'),
            'countries' => trans('payments.countries.title'),
            'min_order_amount' => trans('payments.minimum_order_amount'),
            'max_order_amount' => trans('payments.maximum_order_amount'),
        ];
    }
}
