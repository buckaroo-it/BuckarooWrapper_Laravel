<?php

namespace Buckaroo\Laravel\PaymentMethods;

use App;
use Buckaroo\Laravel\Constants\PaymentMethodMode;
use Buckaroo\Laravel\DTO\PaymentMethod as PaymentMethodDTO;
use Buckaroo\Laravel\PaymentMethods\NoService\NoService;

class PaymentMethod
{
    protected ?string $serviceCode;
    protected array $options = [];

    public function __construct(?PaymentMethodDTO $paymentMethod = null)
    {
        if (!$paymentMethod) {
            $paymentMethod = new PaymentMethodDTO(
                serviceCode: 'noservice',
            );
        }

        $this->serviceCode = $paymentMethod->serviceCode;
        $this->options = $paymentMethod->configs;
    }

    public static function newBuckarooPaymentMethod(PaymentMethodDTO $paymentMethod)
    {
        $class = config("buckaroo.payment_methods.{$paymentMethod->serviceCode}.class");

        if (!$class || !class_exists($class)) {
            $defaultClass = config('buckaroo.use_noservice') ? NoService::class : PaymentGatewayHandler::class;
            $class = new $defaultClass($paymentMethod);
        }

        return $class::make($paymentMethod);
    }

    public static function make(?PaymentMethodDTO $paymentMethod = null): PaymentMethod
    {
        return new static($paymentMethod);
    }

    public function getOption(string $key, $defaultValue = null)
    {
        return data_get($this->options, $key, $defaultValue);
    }

    public function getServiceCode(): ?string
    {
        return $this->serviceCode;
    }

    public function getAdditionalResourceData(): array
    {
        return [];
    }

    public function getDefaultConfigs(): array
    {
        return [
            'mode' => PaymentMethodMode::DISABLED,
        ];
    }

    public function getLogo()
    {
        if (isset($this->options['customizable']) && $this->options['customizable']) {
            return $this->options['logo_url'];
        }

        return $this->buildAssetUrl($this->serviceCode);
    }

    protected function buildAssetUrl(string $serviceCode, string $extension = 'svg'): string
    {
        return asset("/vendor/buckaroo/images/methods/{$serviceCode}.{$extension}");
    }
}
