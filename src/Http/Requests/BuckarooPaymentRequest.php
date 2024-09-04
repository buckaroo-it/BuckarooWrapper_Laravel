<?php

namespace Buckaroo\Laravel\Http\Requests;

use Buckaroo\Laravel\Http\Requests\Rules\Payment\Addressable;
use Buckaroo\Laravel\Http\Requests\Rules\Payment\Articles;
use Buckaroo\Laravel\Http\Requests\Rules\Payment\CreditCard;
use Buckaroo\Laravel\Http\Requests\Rules\Payment\GiftCard;
use Buckaroo\Laravel\Http\Requests\Rules\Payment\Issuer;
use Buckaroo\Laravel\Http\Requests\Rules\Payment\PaymentRules;
use Illuminate\Foundation\Http\FormRequest;

class BuckarooPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $serviceCode = $this->payment['serviceCode'] ?? null;

        $rules = new PaymentRules([
            'payment'                 => 'required',
            'payment.serviceCode'    => 'required'
        ]);

        if(in_array($serviceCode, ['ideal', 'paybybank']))
        {
            $rules = new Issuer($rules);
        }

        if($serviceCode == 'giftcard')
        {
            $rules = new GiftCard($rules);
        }

        if($serviceCode == 'creditcard')
        {
            $rules = new CreditCard($rules);
        }

        if(in_array($serviceCode, ['afterpay', 'afterpaydigiaccept', 'billink', 'in3', 'klarna', 'klarnakp']))
        {
            $rules = new Articles(new Addressable(new Addressable($rules, 'shipping'), 'billing'));
        }

        return $rules->getRules();
    }
}
