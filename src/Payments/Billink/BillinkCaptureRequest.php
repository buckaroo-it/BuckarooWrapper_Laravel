<?php

namespace Buckaroo\Laravel\Payments\Billink;

use Illuminate\Foundation\Http\FormRequest;

class BillinkCaptureRequest extends FormRequest
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
            'originalTransactionKey' => ['required', 'string'],
            'invoice' => ['required', 'string'],
            'amountDebit' => ['required', 'numeric', 'between:0,99999999.99'],
            'articles.*.identifier' => ['required', 'string'],
            'articles.*.description' => ['required', 'string'],
            'articles.*.vatPercentage' => ['required', 'numeric'],
            'articles.*.quantity' => ['required', 'numeric'],
            'articles.*.price' => ['required', 'numeric'],
            'articles.*.priceExcl' => ['required', 'numeric'],
        ];
    }
}
