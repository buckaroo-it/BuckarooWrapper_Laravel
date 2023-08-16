<?php

namespace Buckaroo\Laravel\Payments\BankTransfer;

use Buckaroo\Resources\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;

class BankTransferPayRequest extends FormRequest
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
            'email' => 'required|email',
            'country' => 'required|string',
            'dateDue' => 'required|date',
            'sendMail' => 'required|boolean',
            'customer.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string'
        ];
    }
}
