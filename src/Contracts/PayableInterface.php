<?php

namespace Buckaroo\Laravel\Contracts;

use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

interface PayableInterface
{
    public function getServiceCode(): ?string;

    public function getPaymentSession(): PaymentSessionModel;

    public function getRefundSession(): RefundSessionModel;

    public function setPaymentSession(PaymentSessionModel $paymentSession): self;

    public function getPayload(): array;

    public function getRefundPayload(BuckarooTransaction $buckarooTransaction): array;

    public function handlePayResponse(TransactionResponse $response): TransactionResponse;

    public function getPayAction(): ?string;

    public function getRefundAction(): ?string;
}
