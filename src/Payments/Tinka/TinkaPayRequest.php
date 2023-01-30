<?php

namespace Buckaroo\Laravel\Payments\Tinka;

use Buckaroo\Resources\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;

class TinkaPayRequest extends FormRequest
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
            'paymentMethod' => 'required|string',
            'deliveryMethod' => 'required|string',
            'deliveryDate' => 'required|date',
            'articles.*.type' => 'required',
            'articles.*.description' => 'required|string',
            'articles.*.brand' => 'required|string',
            'articles.*.manufacturer' => 'required|string',
            'articles.*.color' => 'required|string',
            'articles.*.size' => 'required|string',
            'articles.*.quantity' => 'required|numeric|between:0,99999999.99',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
            'articles.*.unitCode' => 'required|string',
            'customer.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string',
            'customer.initials' => 'required|string',
            'customer.birthDate' => 'required|date',
            'billing.recipient.lastNamePrefix' => 'required|string',
            'billing.email' => 'required|email',
            'billing.phone.mobile' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'nullable|string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'shipping.recipient.lastNamePrefix' => 'required|string',
            'shipping.email' => 'required|email',
            'shipping.phone.mobile' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'nullable|string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
        ];
    }
}
