<?php

namespace Buckaroo\Laravel\Wrappers;

use Buckaroo\Laravel\DTO\PaymentMethod;
use Closure;
use Exception;
use Illuminate\Contracts\Container\Container;

class BuckarooManager
{
    /**
     * @var Container
     */
    protected $app;

    protected array $paymentMethods;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function api()
    {
        return $this->app['buckaroo.api'];
    }

    public function getTransactionModelClass(): string
    {
        return $this->app['config']['buckaroo.transaction_model'];
    }

    public function definePaymentMethods(array|Closure $paymentMethods = []): void
    {
        $paymentMethods = is_callable($paymentMethods) ? $paymentMethods() : $paymentMethods;

        foreach ($paymentMethods as $paymentMethod) {
            if (!$paymentMethod instanceof PaymentMethod) {
                throw new Exception('Payment method must be an instance of PaymentMethod');
            }

            $this->paymentMethods[] = $paymentMethod;
        }
    }

    public function getActivePaymentMethods(): array
    {
        return $this->paymentMethods;
    }
}
