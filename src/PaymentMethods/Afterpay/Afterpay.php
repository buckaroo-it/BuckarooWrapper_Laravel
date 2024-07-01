<?php

namespace Buckaroo\Laravel\PaymentMethods\Afterpay;

use Buckaroo\Laravel\Contracts\Capturable;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Buckaroo\Laravel\Traits;

class Afterpay extends PaymentGatewayHandler implements Capturable
{
    use Traits\HasArticles;
    use Traits\HasCustomerDetails;

    public function getPayAction(): ?string
    {
        $paymentSessionDto = $this->paymentSession->toDto();
        if (
            $paymentSessionDto->kind == 'authorization' &&
            !$paymentSessionDto->isAuthorized
        ) {
            return 'authorize';
        }

        return parent::getPayAction();
    }

    public function getServiceCode(): ?string
    {
        return 'afterpay';
    }

    public function getPayload(): array
    {
        return [
            'order' => $this->paymentSessionDTO->order,
            'invoice' => $this->paymentSessionDTO->invoice,
            'billing' => $this->getCustomerDetails('billing'),
            'shipping' => $this->getCustomerDetails('shipping'),
            'articles' => $this->getArticlesPayload(),
        ];
    }

    public function getDefaultConfigs(): array
    {
        return [...parent::getDefaultConfigs(), 'show_financial_warning' => true];
    }

    public function getCapturePayload(): array
    {
        return [];
    }
}
