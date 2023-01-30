<?php

namespace Buckaroo\Laravel\Payments\Payconiq;

use Illuminate\Foundation\Http\FormRequest;

class PayconiqPayRequest extends FormRequest
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
            'description' => 'required|string',
            'invoice' => 'required|string',
        ];
    }
}
