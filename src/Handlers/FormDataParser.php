<?php

namespace Buckaroo\Laravel\Handlers;

use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\Enums\BuckarooTransactionStatus;

class FormDataParser extends ResponseParser implements ResponseParserInterface
{
    public function getAmountDebit(): ?float
    {
        return $this->formatAmount($this->get('brq_amount_debit'));
    }

    public function getAmountCredit(): ?float
    {
        return $this->formatAmount($this->get('brq_amount_credit'));
    }

    public function getAmount(): ?float
    {
        return $this->formatAmount($this->get('brq_amount'));
    }

    public function getCurrency(): ?string
    {
        return $this->get('brq_currency');
    }

    public function getCustomerName(): ?string
    {
        return $this->get('brq_customer_name');
    }

    public function getDescription()
    {
        return $this->get('brq_description');
    }

    public function getInvoice(): ?string
    {
        return $this->get('brq_invoicenumber');
    }

    public function getOrderNumber(): ?string
    {
        return $this->get('brq_ordernumber');
    }

    public function getMutationType()
    {
        return $this->get('brq_mutationtype');
    }

    public function getStatusCode(): ?int
    {
        return $this->get('brq_statuscode');
    }

    public function getSubStatusCode(): ?string
    {
        return $this->get('brq_statuscode_detail');
    }

    public function getSubCodeMessage(): ?string
    {
        return $this->get('brq_statusmessage');
    }

    public function getTransactionMethod()
    {
        return $this->get('brq_transaction_method');
    }

    public function getTransactionType()
    {
        return $this->get('brq_transaction_type');
    }

    public function getTransactionKey(): ?string
    {
        return $this->get('brq_transactions');
    }

    public function getPaymentMethod(): ?string
    {
        return $this->get('brq_transaction_method');
    }

    public function getRelatedTransactionPartialPayment(): ?string
    {
        return $this->get('brq_relatedtransaction_partialpayment');
    }

    public function getAdditionalInformation($propertyName)
    {
        return $this->get('add_' . mb_strtolower($propertyName));
    }

    public function getRefundParentKey(): ?string
    {
        return $this->get('brq_relatedtransaction_refund');
    }

    public function isRefund(): bool
    {
        return $this->getParentKey() !== null;
    }

    public function isSuccess(): bool
    {
        return BuckarooTransactionStatus::fromTransactionStatus($this->getStatusCode()) == BuckarooTransactionStatus::STATUS_PAID;
    }

    public function isPendingProcessing(): bool
    {
        return BuckarooTransactionStatus::fromTransactionStatus($this->getStatusCode()) == BuckarooTransactionStatus::STATUS_PENDING ||
            in_array($this->getSubStatusCode(), ['P190', 'P191']);
    }

    public function getPayerHash(): ?string
    {
        return $this->get('brq_payer_hash');
    }

    public function getPaymentKey(): ?string
    {
        return $this->get('brq_payment');
    }
}