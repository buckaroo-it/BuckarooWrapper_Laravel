<?php

namespace Buckaroo\Laravel\Payments\SEPA;

use Illuminate\Foundation\Http\FormRequest;

class SEPAAuthorizeRequest extends FormRequest
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
            'amountDebit' => 'required|numeric|between:0,99999999.99',
            'iban' => 'required|string|regex:/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/',
            'bic' => 'required|string',
            'collectdate' => 'required|date',
            'mandateReference' => 'required|string',
            'mandateDate' => 'required|date',
            'customer.name' => 'required|string',
        ];
    }
}
