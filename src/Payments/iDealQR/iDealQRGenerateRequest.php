<?php

namespace Buckaroo\Laravel\Payments\iDealQR;

use Illuminate\Foundation\Http\FormRequest;

class iDealQRGenerateRequest extends FormRequest
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
            'description' => 'required|string',
            'minAmount' => 'required|numeric|between:0,99999999.99',
            'maxAmount' => 'required|numeric|between:0,99999999.99',
            'imageSize' => 'required|numeric|between:0,5000',
            'purchaseId' => 'required|string',
            'isOneOff' => 'required|boolean',
            'amount' => 'required|numeric|between:0,99999999.99',
            'amountIsChangeable' => 'required|boolean',
            'expiration' => 'required|date|after:today',
            'isProcessing' => 'required|boolean'
        ];
    }
}
