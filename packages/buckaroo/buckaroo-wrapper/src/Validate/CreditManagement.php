<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;

class CreditManagement
{
    /**
     * @param array $data
     * @return void
     */
    public static function createInvoice(array $data)
    {
        //Validate Create Invoice
        $validator = Validator::make($data, [
            'invoice' => 'required|numeric',
            'applyStartRecurrent' => 'required|string',
            'invoiceAmount' => 'required|numeric',
            'invoiceAmountVAT' => 'required|numeric',
            'invoiceDate' => 'required|date',
            'dueDate' => 'required|date',
            'schemeKey' => 'required|string',
            'maxStepIndex' => 'required|numeric',
            'allowedServices' => 'required|string',

            'debtor.code' => 'required|string',
            'email' => 'required|email',

            'phone.mobile' => 'required|numeric',

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
    public function createInvoiceProdcutLine(array $data)
    {
        $validator = Validator::make($data, [
            'invoice' => 'required|string',
            'description' => 'required|string',
            'invoiceAmount' => 'required|numeric',
            'invoiceDate' => 'required|date',
            'dueDate' => 'required|date',
            'schemeKey' => 'required',
            'poNumber' => 'required',

            'debtor.code' => 'required|string',

            'articles.*.productGroupName' => 'required|string',
            'articles.*.productGroupOrderIndex' => 'required|numeric',
            'articles.*.productOrderIndex' => 'required|numeric',
            'articles.*.type' => 'required|string',
            'articles.*.identifier' => 'required|alpha_num',
            'articles.*.description' => 'required|string',
            'articles.*.quantity' => 'required|numeric',
            'articles.*.unitOfMeasurement' => 'required|string',
            'articles.*.price' => 'required|numeric',
            'articles.*.discountPercentage' => 'required|numeric',
            'articles.*.totalDiscount' => 'required|numeric',
            'articles.*.vatPercentage' => 'required|numeric',
            'articles.*.totalVat' => 'required|numeric',
            'articles.*.totalAmountExVat' => 'required|numeric',
            'articles.*.totalAmount' => 'required|numeric'
        ]);

        return $validator;
    }
}
