<?php

namespace Buckaroo\Laravel\Payments\Webhook;

use Illuminate\Foundation\Http\FormRequest;

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
            'brq_statuscode' => (int)$this->input('brq_statuscode'),
            'brq_statuscode_detail' => (string)$this->input('brq_statuscode_detail'),
            'brq_statusmessage' => (string)$this->input('brq_statusmessage'),
            'brq_transactions' => (string)$this->input('brq_transactions')
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
            'brq_statuscode' => 'nullable|numeric',
            'brq_statuscode_detail' => 'nullable|string',
            'brq_statusmessage' => 'nullable|string',
            'brq_transactions' => 'required|string',
            'brq_websitekey' => 'required|in:' . env('BPE_WEBSITE_KEY')
        ];
    }
}
