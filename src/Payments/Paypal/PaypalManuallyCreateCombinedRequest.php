<?php

namespace Buckaroo\Laravel\Payments\Paypal;

use Illuminate\Foundation\Http\FormRequest;

class PaypalManuallyCreateCombinedRequest extends FormRequest
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
            'includeTransaction' => 'required|boolean',
            'transactionVatPercentage' => 'required|numeric|between:0,999999',
            'configurationCode' => 'required|string',
            'email' => 'required|email',
            'rate_plans.add.*' => 'required',
            'rate_plans.add.startDate' => 'required|date',
            'rate_plans.add.ratePlanCode' => 'required|string',
            'phone.mobile' => 'required|string',
            'debtor.code' => 'required|string',
            'person.firstName' => 'required|string',
            'person.lastName' => 'required|string',
            'person.gender' => 'required',
            'person.culture' => 'nullable|string',
            'person.birthDate' => 'nullable|date',
            'address.street' => 'nullable|string',
            'address.houseNumber' => 'nullable|string',
            'address.zipcode' => 'nullable|string',
            'address.city' => 'nullable|string',
            'address.country' => 'required|string'
        ];
    }
}
