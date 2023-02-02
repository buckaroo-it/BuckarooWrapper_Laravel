<?php

namespace Buckaroo\Laravel\Payments\KlarnaKP;

use Buckaroo\Resources\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;

class KlarnaKPReserveRequest extends FormRequest
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
            'invoice' => 'required|string',
            'gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'operatingCountry' => 'required|string',
            'pno' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^(0[1-9]|[12][0-9]|3[01])(0[1-9]|1[012])([0-9]{4})$/', $value)) {
                        $fail($attribute . ' is not a valid date.');
                    }
                },
            ],
            'billing.recipient.firstName' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'billing.phone.mobile' => 'required|string',
            'billing.email' => 'required|email',
            'shipping.recipient.firstName' => 'required|string',
            'shipping.recipient.lastName' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
            'shipping.email' => 'required|email',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.vatPercentage' => 'required|numeric|between:0,99.99',
            'articles.*.quantity' => 'required|numeric|between:0,999999',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
        ];
    }
}
