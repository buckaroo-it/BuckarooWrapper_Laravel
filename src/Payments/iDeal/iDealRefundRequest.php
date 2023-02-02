<?php

namespace Buckaroo\Laravel\Payments\iDeal;

use Illuminate\Foundation\Http\FormRequest;

class iDealRefundRequest extends FormRequest
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
            'originalTransactionKey' => 'required|string',
            'amountCredit' => 'required|numeric|between:0,99999999.99',
            'clientIP.address' => 'required|string',
            'clientIP.type' => 'required',
            'additionalParameters.initiated_by_magento' => 'required|string',
            'additionalParameters.service_action' => 'required|string'
        ];
    }
}
