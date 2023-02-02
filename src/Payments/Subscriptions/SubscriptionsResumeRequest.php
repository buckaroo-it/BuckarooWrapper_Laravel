<?php

namespace Buckaroo\Laravel\Payments\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionsResumeRequest extends FormRequest
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
            'resumeDate' => 'required|date',
            'subscriptionGuid' => 'required|string',
        ];
    }
}
