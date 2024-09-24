<?php

namespace Buckaroo\Laravel\Handlers;

interface ResponseParserInterface
{
    public function getAmountDebit(): ?float;

    public function getAmountCredit(): ?float;

    public function getAmount(): ?float;

    public function getCurrency(): ?string;

    public function getCustomerName(): ?string;

    public function getDescription();

    public function getInvoice(): ?string;

    public function getOrderNumber(): ?string;

    public function getMutationType();

    public function getStatusCode(): ?int;

    public function getSubStatusCode();

    public function getSubCodeMessage(): ?string;

    public function getTransactionMethod();

    public function getTransactionType();

    public function getTransactionKey(): ?string;

    public function getDataRequest(): ?string;

    public function getPaymentMethod(): ?string;

    public function getRelatedTransactionPartialPayment(): ?string;

    public function getAdditionalInformation($propertyName);

    public function getRefundParentKey(): ?string;

    public function isRefund();

    public function isSuccess(): bool;

    public function isPendingProcessing(): bool;

    public function isPendingApproval(): bool;

    public function getPayerHash(): ?string;

    public function getPaymentKey(): ?string;

    public function isTest(): bool;

    public function hasRedirect(): bool;

    public function getRedirectUrl(): string;

    public function isCanceled(): bool;

    public function isAwaitingConsumer(): bool;

    public function getService($name);
}
