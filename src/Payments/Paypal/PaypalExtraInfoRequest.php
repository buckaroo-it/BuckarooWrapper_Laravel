<?php

namespace Buckaroo\Laravel\Payments\Paypal;

use Illuminate\Foundation\Http\FormRequest;

class PaypalExtraInfoRequest extends FormRequest
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
            'amountDebit' => 'required|numeric|between:0,999999.99',
            'invoice' => 'required|string',
            'customer.name' => 'required|string',
            'address.street' => 'required|string',
            'address.street2' => 'nullable|string',
            'address.city' => 'required|string',
            'address.state' => 'required|string',
            'address.zipcode' => 'required|string',
            'address.country' => 'required|string',
            'phone.mobile' => 'required|string',
        ];
    }
}
