<?php

namespace Buckaroo\Laravel\Payments\CreditManagement;

use Illuminate\Foundation\Http\FormRequest;

class CreditManagementInvoiceProductRequest extends FormRequest
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
            'description' => 'required|string',
            'invoiceAmount' => 'required|numeric|between:0,99999999.99',
            'invoiceDate' => 'required|date',
            'dueDate' => 'required|date',
            'schemeKey' => 'required',
            'poNumber' => 'required',
            'debtor.code' => 'required|string',
            'articles' => 'array',
            'articles.*.productGroupName' => 'required|string',
            'articles.*.productGroupOrderIndex' => 'required|numeric|between:0,99999999.99',
            'articles.*.productOrderIndex' => 'required|numeric|between:0,99999999.99',
            'articles.*.type' => 'required|string',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.quantity' => 'required|numeric|between:0,99999999.99',
            'articles.*.unitOfMeasurement' => 'required|string',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
            'articles.*.discountPercentage' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalDiscount' => 'required|numeric|between:0,99999999.99',
            'articles.*.vatPercentage' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalVat' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalAmountExVat' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalAmount' => 'required|numeric'
        ];
    }
}
