<?php

namespace Buckaroo\Laravel\Payments\Marketplaces;

use Illuminate\Foundation\Http\FormRequest;

class MarketplacesTransferRequest extends FormRequest
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
            'marketplace.amount' => 'required|numeric|between:0,999999.99',
            'marketplace.description' => 'required|string',
            'sellers.*.accountId' => 'required|string',
            'sellers.*.amount' => 'required|numeric|between:0,999999.99',
            'sellers.*.description' => 'required|string',
        ];
    }
}
