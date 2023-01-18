<?php

namespace Buckaroo\BuckarooWrapper\Validate;

use Illuminate\Support\Facades\Validator;
use Buckaroo\Resources\Constants\Gender;

class AfterpayDigiAccept
{

    /**
     * @param array $data
     */

    public static function pay(array $data)
    {
        //Validate Pay
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'order' => 'required|string',
            'invoice' => 'required|string',
            'b2b' => 'required|boolean',
            'addressesDiffer' => 'required|boolean',
            'customerIPAddress' => 'nullable|ip',
            'shippingCosts' => 'required|numeric',
            'costCentre' => 'required|string',
            'department' => 'required|string',
            'establishmentNumber' => 'required|string',
            'billing.recipient.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'billing.recipient.initials' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.recipient.birthDate' => 'required|date',
            'billing.recipient.culture' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'required|string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'billing.phone.mobile' => 'required|string',
            'billing.email' => 'required|email',
            'shipping.recipient.culture' => 'required|string',
            'shipping.recipient.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'shipping.recipient.initials' => 'required|string',
            'shipping.recipient.lastName' => 'required|string',
            'shipping.recipient.companyName' => 'required|string',
            'shipping.recipient.birthDate' => 'required|date',
            'shipping.recipient.chamberOfCommerce' => 'required|string',
            'shipping.recipient.vatNumber' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'required|string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
            'shipping.phone.mobile' => 'required|string',
            'shipping.email' => 'required|email',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.price' => 'required|numeric',
            'articles.*.quantity' => 'required|numeric',
            'articles.*.vatCategory' => 'required|numeric'
        ]);
        return $validator;
    }

    /**
     * @param array $data
     */

    public static function authorize(array $data)
    {
        //Validate Authorize
        $validator = Validator::make($data, [
            'amountDebit' => 'required|numeric',
            'order' => 'required|string',
            'invoice' => 'required|string',
            'b2b' => 'required|boolean',
            'addressesDiffer' => 'required|boolean',
            'customerIPAddress' => 'nullable|ip',
            'shippingCosts' => 'required|numeric',
            'costCentre' => 'required|string',
            'department' => 'required|string',
            'establishmentNumber' => 'required|string',
            'billing.recipient.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'billing.recipient.initials' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.recipient.birthDate' => 'required|date',
            'billing.recipient.culture' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'required|string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'billing.phone.mobile' => 'required|string',
            'billing.email' => 'required|email',
            'shipping.recipient.culture' => 'required|string',
            'shipping.recipient.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'shipping.recipient.initials' => 'required|string',
            'shipping.recipient.lastName' => 'required|string',
            'shipping.recipient.companyName' => 'required|string',
            'shipping.recipient.birthDate' => 'required|date',
            'shipping.recipient.chamberOfCommerce' => 'required|string',
            'shipping.recipient.vatNumber' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'required|string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
            'shipping.phone.mobile' => 'required|string',
            'shipping.email' => 'required|email',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.price' => 'required|numeric',
            'articles.*.quantity' => 'required|numeric',
            'articles.*.vatCategory' => 'required|numeric'
        ]);
        return $validator;
    }

    /**
     * @param array $data
     */

    public static function capture(array $data)
    {
        //Validate Capture
        $validator = Validator::make($data, [
            'originalTransactionKey' => 'required',
            'amountDebit' => 'required|numeric',
            'order' => 'required|string',
            'invoice' => 'required|string',
            'b2b' => 'required|boolean',
            'addressesDiffer' => 'required|boolean',
            'customerIPAddress' => 'nullable|ip',
            'shippingCosts' => 'required|numeric',
            'costCentre' => 'required|string',
            'department' => 'required|string',
            'establishmentNumber' => 'required|string',
            'billing.recipient.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'billing.recipient.initials' => 'required|string',
            'billing.recipient.lastName' => 'required|string',
            'billing.recipient.birthDate' => 'required|date',
            'billing.recipient.culture' => 'required|string',
            'billing.address.street' => 'required|string',
            'billing.address.houseNumber' => 'required|string',
            'billing.address.houseNumberAdditional' => 'required|string',
            'billing.address.zipcode' => 'required|string',
            'billing.address.city' => 'required|string',
            'billing.address.country' => 'required|string',
            'billing.phone.mobile' => 'required|string',
            'billing.email' => 'required|email',
            'shipping.recipient.culture' => 'required|string',
            'shipping.recipient.gender' => 'required|in:' . Gender::MALE . ',' . Gender::FEMALE,
            'shipping.recipient.initials' => 'required|string',
            'shipping.recipient.lastName' => 'required|string',
            'shipping.recipient.companyName' => 'required|string',
            'shipping.recipient.birthDate' => 'required|date',
            'shipping.recipient.chamberOfCommerce' => 'required|string',
            'shipping.recipient.vatNumber' => 'required|string',
            'shipping.address.street' => 'required|string',
            'shipping.address.houseNumber' => 'required|string',
            'shipping.address.houseNumberAdditional' => 'required|string',
            'shipping.address.zipcode' => 'required|string',
            'shipping.address.city' => 'required|string',
            'shipping.address.country' => 'required|string',
            'shipping.phone.mobile' => 'required|string',
            'shipping.email' => 'required|email',
            'articles.*.identifier' => 'required|string',
            'articles.*.description' => 'required|string',
            'articles.*.price' => 'required|numeric',
            'articles.*.quantity' => 'required|numeric',
            'articles.*.vatCategory' => 'required|numeric'
        ]);
        return $validator;
    }

    /**
     * @param array $data
     */

    public static function refund(array $data)
    {
        //Validate Refund
        $validator = Validator::make($data, [
            'amountCredit' => 'required|numeric',
            'invoice' => 'required|string',
            'originalTransactionKey' => 'required|string',
        ]);
        return $validator;
    }
}
