<?php

namespace Buckaroo\Laravel\Payments\Webhook;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class PayloadRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'brq_amount' => (float)$this->input('brq_amount'),
            'brq_currency' => (string)$this->input('brq_currency'),
            'brq_customer_name' => (string)$this->input('brq_customer_name'),
            'brq_invoicenumber' => (string)$this->input('brq_invoicenumber'),
            'brq_issuing_country' => (string)$this->input('brq_issuing_country'),
            'brq_mutationtype' => (string)$this->input('brq_mutationtype'),
            'brq_ordernumber' => (string)$this->input('brq_ordernumber'),
            'brq_payer_hash' => (string)$this->input('brq_payer_hash'),
            'brq_payment' => (string)$this->input('brq_payment'),
            'brq_SERVICE_visa_Authentication' => (string)$this->input('brq_SERVICE_visa_Authentication'),
            'brq_SERVICE_visa_CardExpirationDate' => (string)$this->input('brq_SERVICE_visa_CardExpirationDate'),
            'brq_SERVICE_visa_CardNumberEnding' => (int)$this->input('brq_SERVICE_visa_CardNumberEnding'),
            'brq_SERVICE_visa_Enrolled' => (string)$this->input('brq_SERVICE_visa_Enrolled'),
            'brq_SERVICE_visa_MaskedCreditcardNumber' => (string)$this->input('brq_SERVICE_visa_MaskedCreditcardNumber'),
            'brq_SERVICE_visa_ThreeDsVersion' => (string)$this->input('brq_SERVICE_visa_ThreeDsVersion'),
            'brq_statuscode' => (int)$this->input('brq_statuscode'),
            'brq_statuscode_detail' => (string)$this->input('brq_statuscode_detail'),
            'brq_statusmessage' => (string)$this->input('brq_statusmessage'),
            'brq_test' => (boolean)$this->input('brq_test'),
            'brq_timestamp' => Carbon::parse($this->input('brq_timestamp')),
            'brq_transaction_method' => (string)$this->input('brq_transaction_method'),
            'brq_transaction_type' => (string)$this->input('brq_transaction_type'),
            'brq_transactions' => (string)$this->input('brq_transactions'),
            'brq_websitekey' => (string)$this->input('brq_websitekey'),
            'brq_signature' => (string)$this->input('brq_signature'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'brq_amount' => 'nullable|numeric',
            'brq_currency' => 'nullable|string',
            'brq_customer_name' => 'nullable|string',
            'brq_invoicenumber' => 'required|string',
            'brq_issuing_country' => 'nullable|string',
            'brq_mutationtype' => 'nullable|string',
            'brq_ordernumber' => 'nullable|string',
            'brq_payer_hash' => 'nullable|string',
            'brq_payment' => 'nullable|string',
            'brq_SERVICE_visa_Authentication' => 'nullable|string',
            'brq_SERVICE_visa_CardExpirationDate' => 'nullable|string',
            'brq_SERVICE_visa_CardNumberEnding' => 'nullable|numeric',
            'brq_SERVICE_visa_Enrolled' => 'nullable|string',
            'brq_SERVICE_visa_MaskedCreditcardNumber' => 'nullable|string',
            'brq_SERVICE_visa_ThreeDsVersion' => 'nullable|string',
            'brq_statuscode' => 'nullable|numeric',
            'brq_statuscode_detail' => 'nullable|string',
            'brq_statusmessage' => 'nullable|string',
            'brq_test' => 'required|boolean',
            'brq_timestamp' => 'nullable|date',
            'brq_transaction_method' => 'required|string',
            'brq_transaction_type' => 'required|string',
            'brq_transactions' => 'required|string',
            'brq_websitekey' => 'required|in:' . env('BPE_WEBSITE_KEY'),
            'brq_signature' => 'required|string',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors' => $errors])
        );
    }
}
