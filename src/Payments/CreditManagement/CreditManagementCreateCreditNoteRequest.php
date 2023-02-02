<?php

namespace Buckaroo\Laravel\Payments\CreditManagement;

use Illuminate\Foundation\Http\FormRequest;

class CreditManagementCreateCreditNoteRequest extends FormRequest
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
            'originalInvoiceNumber' => 'required|string',
            'invoiceDate' => 'required|date',
            'invoiceAmount' => 'required|numeric',
            'invoiceAmountVAT' => 'required|numeric',
            'sendCreditNoteMessage' => 'required|email',
        ];
    }
}
