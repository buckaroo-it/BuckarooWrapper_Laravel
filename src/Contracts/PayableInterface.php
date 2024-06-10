<?php

namespace Buckaroo\Laravel\Contracts;

use Buckaroo\Laravel\DTO\PaymentSession;
use Buckaroo\Laravel\DTO\RefundSession;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

interface PayableInterface
{
    public function getServiceCode(): ?string;

    public function getPaymentSession(): PaymentSession;

    public function getRefundSession(): RefundSession;

    public function setPaymentSession($paymentSession): self;

    public function getPayload(): array;

    public function getRefundPayload(BuckarooTransaction $buckarooTransaction): array;

    public function handlePayResponse(TransactionResponse $response): TransactionResponse;

    public function getPayAction(): ?string;

    public function getRefundAction(): ?string;
}
