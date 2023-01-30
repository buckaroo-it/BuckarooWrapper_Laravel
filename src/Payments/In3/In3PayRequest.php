<?php

namespace Buckaroo\Laravel\Payments\In3;

use Buckaroo\Resources\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;

class In3PayRequest extends FormRequest
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
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'order' => 'required|string',
            'invoice' => 'required|string',
            'description' => 'required|string',
            'invoiceDate' => 'required|date',
            'customerType' => 'required|string',
            'email' => 'required|email',
            'phone.mobile' => 'required|string',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.quantity' => 'required|numeric|between:0,999999',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
            'company.companyName' => 'required|string',
            'company.chamberOfCommerce' => 'required|string',
            'customer.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'customer.initials' => 'required|string',
            'customer.lastName' => 'required|string',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string',
            'customer.culture' => 'required|string',
            'customer.birthDate' => 'required|date',
            'address.street' => 'required|string',
            'address.houseNumber' => 'required|string',
            'address.houseNumberAdditional' => 'string',
            'address.zipcode' => 'required|string',
            'address.city' => 'required|string',
            'address.country' => 'required|string',
            'subtotals.*.name' => 'required',
            'subtotals.*.value' => 'required'
        ];
    }
}
