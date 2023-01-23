<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\Resources\Constants\Gender;
use Buckaroo\Resources\Constants\CreditManagementInstallmentInterval;
use Buckaroo\BuckarooClient;


class CreditManagement
{
    private static function buckarooClient()
    {
        return new BuckarooClient(config('buckaroo.website_key'), config('buckaroo.secret_key'), config('buckaroo.mood'));
    }
    /**
     * @param array $data
     * @return void
     */
    public static function createInvoice(array $data)
    {
        //Validate
        if (isset($data['articles'])) {
            //Validate Product Line Validate
            $validator = self::validateInvoiceProduct($data);
        } else {
            //Validate Create Invoice
            $validator = self::validateInvoice($data);
        }

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function createCombinedInvoice(array $data)
    {
        $client = self::buckarooClient();
        //Validate Create Combined Invoice
        $combined = self::validateCombined($data);
        //Validate Calidate Combined Pay
        $pay = self::validateCombinedPay($data);

        if ($combined->fails()){
            return $combined;
        }

        if ($pay->fails()){
            return $pay;
        }

        $invoice = $client->method('credit_management')->manually()->createCombinedInvoice($combined->validated());

        $response = $client->method('sepadirectdebit')->combine($invoice)
            ->pay($pay->validated());


        return $response->toArray();
    }

    /**
     * @param array $data
     * @return void
     */
    public static function createCreditNote(array $data)
    {
        $validator = Validator::make($data, [
            'originalInvoiceNumber' => 'required|string',
            'invoiceDate' => 'required|date',
            'invoiceAmount' => 'required|numeric',
            'invoiceAmountVAT' => 'required|numeric',
            'sendCreditNoteMessage' => 'required|email',
        ]);
        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function addOrUpdateDebtor(array $data)
    {
        $validator = Validator::make($data, [
            'addressUnreachable' => 'required|boolean',
            'emailUnreachable' => 'required|boolean',
            'mobileUnreachable' => 'required|boolean',
            'invoice' => 'required|numeric|between:0,99999999.99',
            'applyStartRecurrent' => 'required|string',
            'invoiceAmount' => 'required|numeric|between:0,99999999.99',
            'invoiceAmountVAT' => 'required|numeric|between:0,99999999.99',
            'invoiceDate' => 'required|date',
            'dueDate' => 'required|date',
            'schemeKey' => 'required|string',
            'maxStepIndex' => 'required|numeric|between:0,99999999.99',
            'allowedServices' => 'required|string',
            'debtor.code' => 'required|string',
            'email' => 'required|email',
            'phone.mobile' => 'required|string',
            'person.culture' => 'required|string',
            'person.title' => 'required|string',
            'person.initials' => 'required|string',
            'person.firstName' => 'required|string',
            'person.lastNamePrefix' => 'required|string',
            'person.lastName' => 'required|string',
            'person.gender' => 'required',
            'company.culture' => 'required|string',
            'company.name' => 'required|string',
            'company.vatApplicable' => 'required|boolean',
            'company.vatNumber' => 'required|string',
            'company.chamberOfCommerce' => 'required|string',
            'address.street' => 'required|string',
            'address.houseNumber' => 'required|string',
            'address.houseNumberSuffix' => 'required|string',
            'address.zipcode' => 'required|string',
            'address.city' => 'required|string',
            'address.state' => 'required|string',
            'address.country' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function createPaymentPlan(array $data)
    {
        $validator = Validator::make($data, [
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
        ]);


        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function terminatePaymentPlan(array $data)
    {
        $validator = Validator::make($data, [
            'includedInvoiceKey' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function pauseInvoice(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function unpauseInvoice(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function invoiceInfo(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'invoices.*.invoiceNumber' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function debtorInfo0(array $data)
    {
        $validator = Validator::make($data, [
            'debtor.code' => 'required|string'
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function addOrUpdateProductLines(array $data)
    {
        $validator = Validator::make($data, [
            'invoiceKey' => 'required|string',
            'articles.*.type' => 'required|string',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.vatPercentage' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalVat' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalAmount' => 'required|numeric|between:0,99999999.99',
            'articles.*.quantity' => 'required|numeric|between:0,99999999.99',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function resumeDebtorFile(array $data)
    {
        $validator = Validator::make($data, [
            'debtorFileGuid' => 'required|string',
        ]);

        return $validator;
    }

    /**
     * @param array $data
     * @return void
     */
    public static function pauseDebtorFile(array $data)
    {
        $validator = Validator::make($data, [
            'debtorFileGuid' => 'required|string',
        ]);

        return $validator;
    }

    private static function validateCombined(array $data): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|numeric|between:0,99999999.99',
            'applyStartRecurrent' => 'required|string',
            'invoiceAmount' => 'required|numeric|between:0,99999999.99',
            'invoiceAmountVAT' => 'required|numeric|between:0,99999999.99',
            'invoiceDate' => 'required|date',
            'dueDate' => 'required|date',
            'schemeKey' => 'required|string',
            'maxStepIndex' => 'required|numeric|between:0,99999999.99',
            'allowedServices' => 'required|string',
            'debtor.code' => 'required|string',
            'email' => 'required|email',
            'phone.mobile' => 'required|string',
            'person.culture' => 'required|string',
            'person.title' => 'required|string',
            'person.initials' => 'required|string',
            'person.firstName' => 'required|string',
            'person.lastNamePrefix' => 'required|string',
            'person.lastName' => 'required|string',
            'person.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'company.culture' => 'required|string',
            'company.name' => 'required|string',
            'company.vatApplicable' => 'required|boolean',
            'company.vatNumber' => 'required|string',
            'company.chamberOfCommerce' => 'required|string',
            'address.street' => 'required|string',
            'address.houseNumber' => 'required|string',
            'address.houseNumberSuffix' => 'required|string',
            'address.zipcode' => 'required|string',
            'address.city' => 'required|string',
            'address.state' => 'required|string',
            'address.country' => 'required|string'
        ]);
        return $validator;
    }

    private static function validateCombinedPay(array $data): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric|min:0',
            'iban' => 'required|string|regex:/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/',
            'bic' => 'required|string|regex:/^[A-Z]{6}[A-Z2-9][A-NP-Z0-9]([A-Z0-9]{3}){0,1}$/',
            'collectdate' => 'required|date',
            'mandateReference' => 'required|string',
            'mandateDate' => 'required|date',
            'customer.name' => 'required|string',
            'invoice' => 'required|numeric|between:0,99999999.99',
        ]);
        return $validator;
    }


    private static function validateInvoice(array $data): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|numeric|between:0,99999999.99',
            'applyStartRecurrent' => 'required|string',
            'invoiceAmount' => 'required|numeric|between:0,99999999.99',
            'invoiceAmountVAT' => 'required|numeric|between:0,99999999.99',
            'invoiceDate' => 'required|date',
            'dueDate' => 'required|date',
            'schemeKey' => 'required|string',
            'maxStepIndex' => 'required|numeric|between:0,99999999.99',
            'allowedServices' => 'required|string',
            'debtor.code' => 'required|string',
            'email' => 'required|email',
            'phone.mobile' => 'required|string',
            'person.culture' => 'required|string',
            'person.title' => 'required|string',
            'person.initials' => 'required|string',
            'person.firstName' => 'required|string',
            'person.lastNamePrefix' => 'required|string',
            'person.lastName' => 'required|string',
            'person.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'company.culture' => 'required|string',
            'company.name' => 'required|string',
            'company.vatApplicable' => 'required|boolean',
            'company.vatNumber' => 'required|string',
            'company.chamberOfCommerce' => 'required|string',
            'address.street' => 'required|string',
            'address.houseNumber' => 'required|string',
            'address.houseNumberSuffix' => 'required|string',
            'address.zipcode' => 'required|string',
            'address.city' => 'required|string',
            'address.state' => 'required|string',
            'address.country' => 'required|string'
        ]);
        return $validator;
    }

    private static function validateInvoiceProduct(array $data): \Illuminate\Validation\Validator
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'description' => 'required|string',
            'invoiceAmount' => 'required|numeric|between:0,99999999.99',
            'invoiceDate' => 'required|date',
            'dueDate' => 'required|date',
            'schemeKey' => 'required',
            'poNumber' => 'required',
            'debtor.code' => 'required|string',
            'articles' => 'array',
            'articles.*.productGroupName' => 'required|string',
            'articles.*.productGroupOrderIndex' => 'required|numeric|between:0,99999999.99',
            'articles.*.productOrderIndex' => 'required|numeric|between:0,99999999.99',
            'articles.*.type' => 'required|string',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.quantity' => 'required|numeric|between:0,99999999.99',
            'articles.*.unitOfMeasurement' => 'required|string',
            'articles.*.price' => 'required|numeric|between:0,99999999.99',
            'articles.*.discountPercentage' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalDiscount' => 'required|numeric|between:0,99999999.99',
            'articles.*.vatPercentage' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalVat' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalAmountExVat' => 'required|numeric|between:0,99999999.99',
            'articles.*.totalAmount' => 'required|numeric'
        ]);
        return $validator;
    }
}
