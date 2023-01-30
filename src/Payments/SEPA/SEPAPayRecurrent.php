<?php

namespace Buckaroo\Laravel\Payments\SEPA;

use Illuminate\Foundation\Http\FormRequest;

class SEPAPayRecurrent extends FormRequest
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
            'originalTransactionKey' => 'required|string',
            'invoice' => 'required|string',
            'collectdate' => 'required|date',
        ];
    }
}
