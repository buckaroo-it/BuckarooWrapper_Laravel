<?php

namespace Buckaroo\Laravel\Payments\Billink;

use Illuminate\Foundation\Http\FormRequest;

class BillinkPayRequest extends FormRequest
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
            'amountDebit' => ['required', 'numeric', 'between:0,99999999.99'],
            'order' => ['required', 'string'],
            'invoice' => ['required', 'string'],
            'trackAndTrace' => ['required', 'string'],
            'vATNumber' => ['required', 'string'],
            'billing.recipient.category' => ['required', 'string'],
            'billing.recipient.careOf' => ['required', 'string'],
            'billing.recipient.title' => ['required', 'string'],
            'billing.recipient.initials' => ['required', 'string'],
            'billing.recipient.firstName' => ['required', 'string'],
            'billing.recipient.lastName' => ['required', 'string'],
            'billing.recipient.birthDate' => ['required', 'date'],
            'billing.recipient.chamberOfCommerce' => ['required', 'string'],
            'billing.address.street' => ['required', 'string'],
            'billing.address.houseNumber' => ['required', 'string'],
            'billing.address.houseNumberAdditional' => ['string'],
            'billing.address.zipcode' => ['required', 'string'],
            'billing.address.city' => ['required', 'string'],
            'billing.address.country' => ['required', 'string'],
            'billing.phone.mobile' => ['required', 'string'],
            'billing.phone.landline' => ['required', 'string'],
            'billing.email' => ['required', 'string', 'email'],
            'shipping.recipient.category' => ['required','string'],
            'shipping.recipient.careOf' => ['required', 'string'],
            'shipping.recipient.title' => ['required', 'string'],
            'shipping.recipient.initials' => ['required', 'string'],
            'shipping.recipient.firstName' => ['required', 'string'],
            'shipping.recipient.lastName' => ['required', 'string'],
            'shipping.recipient.birthDate' => ['required', 'date'],
            'shipping.address.street' => ['required', 'string'],
            'shipping.address.houseNumber' => ['required', 'string'],
            'shipping.address.houseNumberAdditional' => ['string'],
            'shipping.address.zipcode' => ['required', 'string'],
            'shipping.address.city' => ['required', 'string'],
            'shipping.address.country' => ['required', 'string'],
            'articles.*.identifier' => ['required', 'string'],
            'articles.*.description' => ['required', 'string'],
            'articles.*.vatPercentage' => ['required', 'numeric'],
            'articles.*.quantity' => ['required', 'numeric'],
            'articles.*.price' => ['required', 'numeric'],
            'articles.*.priceExcl' => ['required', 'numeric']
        ];
    }
}
