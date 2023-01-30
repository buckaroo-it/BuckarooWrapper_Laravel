<?php

namespace Buckaroo\Laravel\Payments\BuckarooVoucher;

use Illuminate\Foundation\Http\FormRequest;

class BuckarooVoucherCreateRequest extends FormRequest
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
            'usageType' => 'required|numeric',
            'validFrom' => 'required|date',
            'validUntil' => 'required|date',
            'creationBalance' => 'required|numeric'
        ];
    }
}
