<?php

namespace Buckaroo\Laravel\PaymentMethods\CreditCard;

use Buckaroo\Laravel\PaymentMethods\DefaultConfigFormRequest;
use Illuminate\Validation\Rule;

class ConfigFormRequest extends DefaultConfigFormRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'separate_auth_and_capture' => 'nullable|bool',
            'enabled_cards' => 'nullable|array',
            'enabled_cards.*' => ['required', Rule::exists('payment_methods', 'id')],
        ];
    }

    public function attributes(): array
    {
        return [
            'separate_auth_and_capture' => trans('payments.separate_auth_and_capture'),
            'enabled_cards' => trans('payments.giftcard.enabled'),
            'enabled_cards.*' => trans('payments.giftcard.enabled'),
        ];
    }
}
