<?php

namespace Buckaroo\Laravel\PaymentMethods;

use Buckaroo\Laravel\Contracts\PayableInterface;
use Buckaroo\Laravel\Contracts\PaymentSessionModel;
use Buckaroo\Laravel\Contracts\RefundSessionModel;
use Buckaroo\Laravel\DTO\PaymentSession as PaymentSessionDTO;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

class PaymentGatewayHandler extends PaymentMethod implements PayableInterface
{
    protected ?PaymentSessionModel $paymentSession;
    protected ?PaymentSessionDTO $paymentSessionDTO;
    protected ?RefundSessionModel $refundSession;

    public function getPaymentSession(): PaymentSessionModel
    {
        return $this->paymentSession;
    }

    public function setPaymentSession(PaymentSessionModel $paymentSession): self
    {
        $this->paymentSession = $paymentSession;
        $this->paymentSessionDTO = $paymentSession->toDto();

        return $this;
    }

    public function getRefundSession(): RefundSessionModel
    {
        return $this->refundSession;
    }

    public function setRefundSession(RefundSessionModel $refundSession): self
    {
        $this->refundSession = $refundSession;

        return $this;
    }

    public function getPayload(): array
    {
        return [];
    }

    public function getRefundPayload(BuckarooTransaction $buckarooTransaction): array
    {
        return [];
    }

    public function handlePayResponse(TransactionResponse $response): TransactionResponse
    {
        return $response;
    }

    public function getPayAction(): ?string
    {
        return 'pay';
    }

    public function getRefundAction(): ?string
    {
        return 'refund';
    }
}
