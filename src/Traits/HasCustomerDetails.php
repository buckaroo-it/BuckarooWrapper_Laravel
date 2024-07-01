<?php

namespace Buckaroo\Laravel\Traits;

use Buckaroo\Resources\Constants\RecipientCategory;
use Str;

trait HasCustomerDetails
{
    public function getInitials($type): string
    {
        $recipientType = $type === 'billing' ? $this->getBillingAddress() : $this->getShippingAddress();
        $nameParts = [$recipientType->firstName, $recipientType->lastName];

        $firstInitial = isset($nameParts[0]) ? Str::upper($nameParts[0][0]) : '';
        $lastInitial = count($nameParts) > 1 ? Str::upper($nameParts[count($nameParts) - 1][0]) : '';

        return $firstInitial . $lastInitial;
    }

    protected function getBillingAddress()
    {
        return $this->paymentSessionDTO->customer->billingAddress ?? [];
    }

    protected function getShippingAddress()
    {
        return $this->paymentSessionDTO->customer->shippingAddress ?? $this->getBillingAddress();
    }

    protected function getShippingValue(string $key, mixed $default = '')
    {
        return data_get($this->getShippingAddress(), $key, $default) ?? $default;
    }

    protected function getCustomerDetails($type): array
    {
        return [
            'recipient' => $this->getRecipientPayload($type),
            'address' => $this->getAddressPayload($type),
            'phone' => [
                'mobile' => data_get($this->getCustomer(), 'phoneNumber', ''),
            ],
            'email' => $this->getCustomerValue('email'),
        ];
    }

    public function getRecipientPayload($type): array
    {
        $recipientType = $type === 'billing' ? $this->getBillingAddress() : $this->getShippingAddress();
        $customerDTO = $this->getCustomer();

        return [
            'category' => filled($customerDTO->chamberOfCommerce) ? RecipientCategory::COMPANY : RecipientCategory::PERSON,
            'chamberOfCommerce' => $customerDTO->chamberOfCommerce ?? '',
            'firstName' => $recipientType->firstName,
            'lastName' => $recipientType->lastName,
            'companyName' => $recipientType->company,
            'birthDate' => $this->getCustomerValue('birthDate'),
        ];
    }

    protected function getCustomer()
    {
        return $this->paymentSessionDTO->customer;
    }

    protected function getCustomerValue(string $key, mixed $default = '')
    {
        return data_get($this->getCustomer(), $key, $default) ?? $default;
    }

    public function getAddressPayload($type): array
    {
        $recipientType = $type === 'billing' ? $this->getBillingAddress() : $this->getShippingAddress();

        return [
            'street' => $recipientType->line1 ?? $this->getBillingValue('line1', ''),
            'houseNumber' => $recipientType->line2 ?? $this->getBillingValue('line2', ''),
            'zipcode' => $recipientType->postalCode ?? $this->getBillingValue('postalCode', ''),
            'city' => $recipientType->city ?? $this->getBillingValue('city', ''),
            'companyName' => $recipientType->company ?? $this->getBillingValue('company', ''),
            'country' => $recipientType->countryCode ?? $this->getBillingValue('countryCode', ''),
        ];
    }

    protected function getBillingValue(string $key, mixed $default = '')
    {
        return data_get($this->getBillingAddress(), $key, $default) ?? $default;
    }
}
