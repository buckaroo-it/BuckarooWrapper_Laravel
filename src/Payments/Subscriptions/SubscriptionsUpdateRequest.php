<?php

namespace Buckaroo\Laravel\Payments\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'subscriptionGuid' => 'required|string',
            'configurationCode' => 'required|string',
            'rate_plans.update.*' => 'required',
            'rate_plans.update.ratePlanGuid' => 'required|string',
            'rate_plans.update.startDate' => 'required|date',
            'rate_plans.update.endDate' => 'required|date',
            'rate_plans.update.charge.*' => 'required',
            'rate_plans.update.charge.ratePlanChargeGuid' => 'required|string',
            'rate_plans.update.charge.baseNumberOfUnits' => 'required|numeric|between:0,999999',
            'rate_plans.update.charge.pricePerUnit' => 'required|numeric|between:0,999999.99',
        ];
    }
}
