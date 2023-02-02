<?php

namespace Buckaroo\Laravel\Payments\GiftCard;

use Illuminate\Foundation\Http\FormRequest;

class GiftCardPayRequest extends FormRequest
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
            'amountDebit' => 'required|numeric',
            'invoice' => 'required|string',
            'name' => 'required|string',
            'intersolveCardnumber' => 'required|string',
            'intersolvePIN' => 'required|string',
        ];
    }
}
