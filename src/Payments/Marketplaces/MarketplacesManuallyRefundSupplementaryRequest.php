<?php

namespace Buckaroo\Laravel\Payments\Marketplaces;

use Illuminate\Foundation\Http\FormRequest;

class MarketplacesManuallyRefundSupplementaryRequest extends FormRequest
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
            'sellers.*.accountId' => 'required|string',
            'sellers.*.description' => 'required|string',
        ];
    }
}
