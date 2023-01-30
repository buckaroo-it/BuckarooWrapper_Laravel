<?php

namespace Buckaroo\Laravel\Payments\Emandates;

use Illuminate\Foundation\Http\FormRequest;

class EmandatesCreateMandateRequest extends FormRequest
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
            'mandateid' => 'required|string',
            'emandatereason' => 'required|string',
            'purchaseid' => 'required|string',
        ];
    }
}
