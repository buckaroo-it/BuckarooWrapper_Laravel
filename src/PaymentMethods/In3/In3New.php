<?php

namespace Buckaroo\Laravel\PaymentMethods\In3;

use Buckaroo\Resources\Constants\RecipientCategory;

class In3New extends In3
{
    public function getServiceCode(): ?string
    {
        return 'in3';
    }

    public function getPayload(): array
    {
        $billingPayload = $this->getCustomerDetails('billing');
        $shippingPayload = $this->getCustomerDetails('shipping');

        $customerDTO = $this->getCustomer();
        $billingPayload['recipient']['customerNumber'] = $customerDTO->customerNumber;
        $billingPayload['recipient']['category'] = filled(data_get($billingPayload['recipient'], 'chamberOfCommerce')) ? 'B2B' : 'B2C';
        $shippingPayload['recipient']['category'] = $billingPayload['recipient']['category'];

        return [
            'billing' => $billingPayload,
            'shipping' => $shippingPayload,
            'articles' => $this->getArticlesPayload(),
        ];
    }

    public function getRecipientPayload($type): array
    {
        $recipientType = $type === 'billing' ? $this->getBillingAddress() : $this->getShippingAddress();

        return [
            'category' => filled($recipientType->company) ? RecipientCategory::COMPANY : RecipientCategory::PERSON,
            'firstName' => $recipientType->firstName,
            'lastName' => $recipientType->lastName,
            'companyName' => $recipientType->company,
            'birthDate' => $this->getCustomerValue('birthDate'),
        ];
    }
}
