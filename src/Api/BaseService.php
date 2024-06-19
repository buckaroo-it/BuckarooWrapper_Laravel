<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Contracts;
use Buckaroo\Laravel\DTO\PaymentMethod as PaymentMethodDTO;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Buckaroo\Transaction\Response\TransactionResponse;

abstract class BaseService
{
    protected array $payload = [];
    protected PaymentGatewayHandler $paymentGatewayHandler;
    protected PaymentMethodDTO $paymentMethod;
    protected Contracts\PaymentSessionModel $paymentSession;

    public static function make(): static
    {
        return new static();
    }

    public function isRequestValid(): bool
    {
        $paymentSessionDTO = $this->paymentSession->toDto();

        return Buckaroo::api()->validCredentials() &&
            Buckaroo::api()->inTestMode() == $paymentSessionDTO->isTest &&
            $this->paymentMethod->getConfig('mode') == ($paymentSessionDTO->isTest ? 'test' : 'live');
    }

    public function setPaymentMethod(PaymentMethodDTO $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function setPaymentSession(Contracts\PaymentSessionModel $paymentSession): self
    {
        $this->paymentSession = $paymentSession;

        return $this;
    }

    protected function createRedirectTransactionResponse(string $redirectUrl): TransactionResponse
    {
        return new TransactionResponse([], ['RequiredAction' => ['RedirectURL' => $redirectUrl, 'Name' => 'Redirect']]);
    }
}
