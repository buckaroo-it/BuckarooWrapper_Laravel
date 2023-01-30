<?php

namespace Buckaroo\Laravel\Payments\BuckarooWallet;

use Illuminate\Foundation\Http\FormRequest;

class BuckarooWalletCreateWalletRequest extends FormRequest
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
            'walletId' => 'required|numeric',
            'email' => 'required|email',
            'customer.firstName' => 'required|string',
            'customer.lastName' => 'required|string',
            'bankAccount.iban' => 'required|string|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/',

        ];
    }
}
