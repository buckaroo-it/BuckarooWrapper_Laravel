<?php

namespace Buckaroo\Laravel\Payments\BuckarooWallet;

use Illuminate\Foundation\Http\FormRequest;

class BuckarooWalletWithdrawalRequest extends FormRequest
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
            'amountDebit' => 'required|numeric',
            'walletId' => 'required|numeric',
        ];
    }
}
