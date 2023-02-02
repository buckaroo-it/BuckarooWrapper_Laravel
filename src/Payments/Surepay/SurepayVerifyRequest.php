<?php

namespace Buckaroo\Laravel\Payments\Surepay;

use Illuminate\Foundation\Http\FormRequest;

class SurepayVerifyRequest extends FormRequest
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
            'bankAccount.iban' => 'required|string|regex:/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/',
            'bankAccount.accountName' => 'required|string',
        ];
    }
}
