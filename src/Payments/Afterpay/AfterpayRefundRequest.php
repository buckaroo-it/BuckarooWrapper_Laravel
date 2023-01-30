<?php

namespace Buckaroo\Laravel\Payments\Afterpay;

use Illuminate\Foundation\Http\FormRequest;

class AfterpayRefundRequest extends FormRequest
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
            'originalTransactionKey' => 'required|string',
            'invoice' => 'required|string',
            'amountCredit' => 'required|numeric|between:0,99999999.99',
            'clientIP' => 'nullable|string',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.vatPercentage' => 'required|numeric',
            'articles.*.quantity' => 'required|integer',
            'articles.*.price' => 'required|numeric'
        ];
    }
}
