<?php

namespace Buckaroo\Laravel\Payments\CreditManagement;

use Illuminate\Foundation\Http\FormRequest;

class CreditManagementAddOrUpdateProductLinesRequest extends FormRequest
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
            'invoiceKey' => 'required|string',
            'articles.*.type' => 'required|string',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.vatPercentage' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalVat' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalAmount' => 'required|numeric|between:0,99999999.99',
            'articles.*.quantity' => 'required|numeric|between:0,99999999.99',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
        ];
    }
}
