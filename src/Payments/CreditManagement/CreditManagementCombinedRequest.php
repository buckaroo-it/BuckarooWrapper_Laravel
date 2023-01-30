<?php

namespace Buckaroo\Laravel\Payments\CreditManagement;

use Buckaroo\Resources\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;

class CreditManagementCombinedRequest extends FormRequest
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
            'invoice' => 'required|numeric|between:0,99999999.99',
            'applyStartRecurrent' => 'required|string',
            'invoiceAmount' => 'required|numeric|between:0,99999999.99',
            'invoiceAmountVAT' => 'required|numeric|between:0,99999999.99',
            'invoiceDate' => 'required|date',
            'dueDate' => 'required|date',
            'schemeKey' => 'required|string',
            'maxStepIndex' => 'required|numeric|between:0,99999999.99',
            'allowedServices' => 'required|string',
            'debtor.code' => 'required|string',
            'email' => 'required|email',
            'phone.mobile' => 'required|string',
            'person.culture' => 'required|string',
            'person.title' => 'required|string',
            'person.initials' => 'required|string',
            'person.firstName' => 'required|string',
            'person.lastNamePrefix' => 'required|string',
            'person.lastName' => 'required|string',
            'person.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'company.culture' => 'required|string',
            'company.name' => 'required|string',
            'company.vatApplicable' => 'required|boolean',
            'company.vatNumber' => 'required|string',
            'company.chamberOfCommerce' => 'required|string',
            'address.street' => 'required|string',
            'address.houseNumber' => 'required|string',
            'address.houseNumberSuffix' => 'required|string',
            'address.zipcode' => 'required|string',
            'address.city' => 'required|string',
            'address.state' => 'required|string',
            'address.country' => 'required|string'
        ];
    }
}
