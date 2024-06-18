<?php

namespace Buckaroo\Laravel\PaymentMethods\GiftCard;

use Buckaroo\Laravel\PaymentMethods\DefaultConfigFormRequest;
use Illuminate\Validation\Rule;

class ConfigFormRequest extends DefaultConfigFormRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'enabled_cards' => 'nullable|array',
            'enabled_cards.*' => ['required', Rule::exists('payment_methods', 'id')],
        ];
    }

    public function attributes(): array
    {
        return [
            'enabled_cards' => trans('payments.giftcard.enabled'),
            'enabled_cards.*' => trans('payments.giftcard.enabled'),
        ];
    }
}
