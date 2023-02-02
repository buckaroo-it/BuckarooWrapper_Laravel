<?php

namespace Buckaroo\Laravel\Payments\KlarnaKP;

use Illuminate\Foundation\Http\FormRequest;

class KlarnaKPPayRequest extends FormRequest
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
            'reservationNumber' => 'required|string',
            'serviceParameters.articles.*.identifier' => 'required|string',
            'serviceParameters.articles.*.quantity' => 'required|numeric|between:0,999999',
        ];
    }
}
