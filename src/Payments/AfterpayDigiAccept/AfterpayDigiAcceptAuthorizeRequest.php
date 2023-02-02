<?php

namespace Buckaroo\Laravel\Payments\AfterpayDigiAccept;

use Buckaroo\Resources\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;

class AfterpayDigiAcceptAuthorizeRequest extends FormRequest
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
            'b2b' => 'required|boolean',
            'addressesDiffer' => 'required|boolean',
            'customerIPAddress' => 'nullable|ip',
            'shippingCosts' => 'required|numeric',
            'costCentre' => 'required|string',
            'department' => 'required|string',
            'establishmentNumber' => 'required|string',
            'billing.recipient.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'billing.recipient.initials' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.recipient.birthDate' => 'required|date',
            'billing.recipient.culture' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'required|string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'billing.phone.mobile' => 'required|string',
            'billing.email' => 'required|email',
            'shipping.recipient.culture' => 'required|string',
            'shipping.recipient.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'shipping.recipient.initials' => 'required|string',
            'shipping.recipient.lastName' => 'required|string',
            'shipping.recipient.companyName' => 'required|string',
            'shipping.recipient.birthDate' => 'required|date',
            'shipping.recipient.chamberOfCommerce' => 'required|string',
            'shipping.recipient.vatNumber' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'required|string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
            'shipping.phone.mobile' => 'required|string',
            'shipping.email' => 'required|email',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.price' => 'required|numeric',
            'articles.*.quantity' => 'required|numeric',
            'articles.*.vatCategory' => 'required|numeric'
        ];
    }
}
