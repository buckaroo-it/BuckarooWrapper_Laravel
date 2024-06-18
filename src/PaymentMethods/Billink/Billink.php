<?php

namespace Buckaroo\Laravel\PaymentMethods\Billink;

use Buckaroo\Laravel\Contracts\Capturable;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Buckaroo\Laravel\Traits\HasArticles;
use Buckaroo\Laravel\Traits\HasCustomerDetails;

class Billink extends PaymentGatewayHandler implements Capturable
{
    use HasArticles;
    use HasCustomerDetails;

    protected string $configFormRequest = ConfigFormRequest::class;

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
        return 'billink';
    }

    public function getPayload(): array
    {
        $billingPayload = $this->getCustomerDetails('billing');
        $shippingPayload = $this->getCustomerDetails('shipping');

        $billingPayload['recipient']['category'] = filled($billingPayload['recipient']['chamberOfCommerce']) ? 'B2B' : 'B2C';
        $shippingPayload['recipient']['category'] = $billingPayload['recipient']['category'];

        return [
            'billing' => $billingPayload,
            'shipping' => $shippingPayload,
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
