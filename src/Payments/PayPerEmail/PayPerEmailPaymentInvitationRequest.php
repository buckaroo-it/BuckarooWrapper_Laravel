<?php

namespace Buckaroo\Laravel\Payments\PayPerEmail;

use Buckaroo\Resources\Constants\Gender;
use Illuminate\Foundation\Http\FormRequest;

class PayPerEmailPaymentInvitationRequest extends FormRequest
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
            'merchantSendsEmail' => 'required|boolean',
            'email' => 'required|email',
            'expirationDate' => 'required|date',
            'paymentMethodsAllowed' => 'required|string',
            'attachment' => 'nullable|file',
            'customer.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string',
            'attachments.*.name' => 'nullable|string',
        ];
    }
}
