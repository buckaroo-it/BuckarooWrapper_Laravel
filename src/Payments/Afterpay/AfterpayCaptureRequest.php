<?php

namespace Buckaroo\Laravel\Payments\Afterpay;

use Illuminate\Foundation\Http\FormRequest;

class AfterpayCaptureRequest extends FormRequest
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
            'originalTransactionKey' => 'required|string',
            'invoice' => 'required|string',
            'clientIP' => 'nullable|string',
            'billing.recipient.category' => 'required|string',
            'billing.recipient.careOf' => 'required|string',
            'billing.recipient.title' => 'required|string',
            'billing.recipient.firstName' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.recipient.birthDate' => 'required|date',
            'billing.recipient.conversationLanguage' => 'required|string',
            'billing.recipient.identificationNumber' => 'required|string',
            'billing.recipient.customerNumber' => 'required|string',
            'billing.address.street' => 'nullable|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'nullable|string',
            'billing.address.zipcode' => 'nullable|string',
            'billing.address.city' => 'nullable|string',
            'billing.address.phone.mobile' => 'nullable|string',
            'billing.address.phone.landline' => 'nullable|string',
            'billing.address.country' => 'nullable|string',
            'billing.address.email' => 'nullable|email',
            'shipping.recipient.category' => 'nullable|string',
            'shipping.recipient.careOf' => 'nullable|string',
            'shipping.recipient.companyName' => 'nullable|string',
            'shipping.recipient.firstName' => 'nullable|string',
            'shipping.recipient.lastName' => 'nullable|string',
            'shipping.recipient.chamberOfCommerce' => 'nullable|string',
            'shipping.address.street' => 'nullable|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'nullable|string',
            'shipping.address.zipcode' => 'nullable|string',
            'shipping.address.city' => 'nullable|string',
            'shipping.address.country' => 'nullable|string',
            'articles.*.identifier' => 'nullable|string',
            'articles.*.description' => 'nullable|string',
            'articles.*.vatPercentage' => 'nullable|numeric',
            'articles.*.quantity' => 'nullable|integer',
            'articles.*.price' => 'nullable|numeric',
        ];
    }
}
