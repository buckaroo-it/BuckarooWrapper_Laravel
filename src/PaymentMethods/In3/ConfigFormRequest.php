<?php

namespace Buckaroo\Laravel\PaymentMethods\In3;

use Buckaroo\Laravel\PaymentMethods\DefaultConfigFormRequest;

class ConfigFormRequest extends DefaultConfigFormRequest
{
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'show_financial_warning' => 'nullable|bool',
            'version' => 'nullable|in:v2,v3',
        ];
    }

    public function attributes(): array
    {
        return [
            ...parent::attributes(),
            'show_financial_warning' => trans('payments.financial_warning.title'),
            'version' => trans('payments.in3.api_version'),
        ];
    }
}
