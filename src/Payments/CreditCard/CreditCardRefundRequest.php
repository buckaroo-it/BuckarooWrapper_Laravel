<?php

namespace Buckaroo\Laravel\Payments\CreditCard;

use Illuminate\Foundation\Http\FormRequest;

class CreditCardRefundRequest extends FormRequest
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
            'amountCredit' => 'required|numeric|between:0,99999999.99',
            'invoice' => 'string',
            'originalTransactionKey' => 'required|string',
            'name' => 'required|string',
        ];
    }
}