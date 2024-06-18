<?php

namespace Buckaroo\Laravel\PaymentMethods\In3;

use Buckaroo\Laravel\DTO\PaymentMethod as PaymentMethodDTO;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Buckaroo\Laravel\PaymentMethods\PaymentMethod;
use Buckaroo\Laravel\Traits\HasArticles;
use Buckaroo\Laravel\Traits\HasCustomerDetails;

class In3 extends PaymentGatewayHandler
{
    use HasArticles;
    use HasCustomerDetails;

    protected string $configFormRequest = ConfigFormRequest::class;

    public static function make(?PaymentMethodDTO $paymentMethod = null): PaymentMethod
    {
        $class = $paymentMethod?->getConfig('version') == 'v2' ?
            config('buckaroo.payment_methods.in3.old_class') :
            config('buckaroo.payment_methods.in3.new_class');

        return new $class($paymentMethod);
    }

    public function getDefaultConfigs(): array
    {
        return [
            ...parent::getDefaultConfigs(),
            'show_financial_warning' => true,
            'frontend_label' => 'iDeal In3',
            'version' => 'v3',
        ];
    }

    public function getLogo()
    {
        $logoVersion = $this->options['version'] ?? 'v3';

        return data_get($this->getAdditionalResourceData(), "version_logos.{$logoVersion}");
    }

    public function getAdditionalResourceData(): array
    {
        return [
            'version_logos' => [
                'v2' => $this->buildAssetUrl('in3'),
                'v3' => $this->buildAssetUrl('in3-ideal'),
            ],
        ];
    }
}
