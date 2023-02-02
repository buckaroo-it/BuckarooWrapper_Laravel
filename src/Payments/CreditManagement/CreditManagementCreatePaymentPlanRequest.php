<?php

namespace Buckaroo\Laravel\Payments\CreditManagement;

use Buckaroo\Resources\Constants\CreditManagementInstallmentInterval;
use Illuminate\Foundation\Http\FormRequest;

class CreditManagementCreatePaymentPlanRequest extends FormRequest
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
            'description' => 'required|string',
            'includedInvoiceKey' => 'required|string',
            'dossierNumber' => 'required|string',
            'installmentCount' => 'required|numeric|between:0,99999999.99',
            'initialAmount' => 'required|numeric|between:0,99999999.99',
            'startDate' => 'required|date',
            'interval' => 'required|in:' . CreditManagementInstallmentInterval::DAY . ',' . CreditManagementInstallmentInterval::TWODAYS . ',' . CreditManagementInstallmentInterval::WEEK . ',' . CreditManagementInstallmentInterval::TWOWEEKS . ',' . CreditManagementInstallmentInterval::HALFMONTH . ',' . CreditManagementInstallmentInterval::MONTH . ',' . CreditManagementInstallmentInterval::TWOMONTHS . ',' . CreditManagementInstallmentInterval::QUARTERYEAR . ',' . CreditManagementInstallmentInterval::HALFYEAR . ',' . CreditManagementInstallmentInterval::YEAR,
            'paymentPlanCostAmount' => 'required|numeric|between:0,99999999.99',
            'paymentPlanCostAmountVat' => 'required|string',
            'recipientEmail' => 'required|email',

        ];
    }
}
