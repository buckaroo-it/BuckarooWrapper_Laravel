<?php

namespace Buckaroo\Laravel\Payments\CreditManagement;

use Illuminate\Foundation\Http\FormRequest;

class CreditManagementCombinedPayRequest extends FormRequest
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
            'amountDebit' => 'required|numeric|min:0',
            'iban' => 'required|string|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/',
            'bic' => 'required|string|regex:/^[A-Z]{6}[A-Z2-9][A-NP-Z0-9]([A-Z0-9]{3}){0,1}$/',
            'collectdate' => 'required|date',
            'mandateReference' => 'required|string',
            'mandateDate' => 'required|date',
            'customer.name' => 'required|string',
            'invoice' => 'required|numeric|between:0,99999999.99',
        ];
    }
}
