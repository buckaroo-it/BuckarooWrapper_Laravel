<?php

namespace Buckaroo\Laravel\PaymentMethods\In3;

use Arr;

class In3Old extends In3
{
    public function getServiceCode(): ?string
    {
        return 'in3Old';
    }

    public function getPayload(): array
    {
        return [
            'invoiceDate' => now()->format('Y-m-d'),
            'customerType' => 'Debtor',
            'customer' => array_merge(
                Arr::except($this->getRecipientPayload('billing'), ['category', 'firstName']),
                [
                    'initials' => $this->getInitials('billing'),
                    'culture' => $this->getCustomerValue('locale'),
                ]
            ),
            'address' => $this->getAddressPayload('billing'),
            'articles' => Arr::map($this->getArticlesPayload(), fn ($article) => Arr::except($article, ['vatPercentage'])),
            'phone' => [
                'mobile' => $this->getBillingValue('phoneNumber') ?: $this->getCustomerValue('phoneNumber'),
            ],
            'email' => $this->getCustomerValue('email'),
        ];
    }
}
