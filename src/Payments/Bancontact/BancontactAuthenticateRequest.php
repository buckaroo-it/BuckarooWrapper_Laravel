<?php

namespace Buckaroo\Laravel\Payments\Bancontact;

use Illuminate\Foundation\Http\FormRequest;

class BancontactAuthenticateRequest extends FormRequest
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
            'amountDebit' => 'required|numeric',
            'description' => 'required|string',
            'savetoken' => 'required|string'
        ];
    }
}