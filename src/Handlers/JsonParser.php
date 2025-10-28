<?php

namespace Buckaroo\Laravel\Handlers;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Resources\Constants\ResponseStatus;

class JsonParser extends ResponseParser
{
    public function getAmountDebit(): ?float
    {
        return $this->formatAmount($this->getCaseInsensitive('AmountDebit'));
    }

    public function getAmountCredit(): ?float
    {
        return $this->formatAmount($this->getCaseInsensitive('AmountCredit'));
    }

    public function getAmount(): ?float
    {
        return $this->formatAmount($this->getCaseInsensitive('Amount')) ?? $this->getAmountDebit();
    }

    public function getCurrency(): ?string
    {
        return $this->getCaseInsensitive('Currency');
    }

    public function getCustomerName(): ?string
    {
        return $this->getCaseInsensitive('CustomerName');
    }

    public function getDescription()
    {
        return $this->getCaseInsensitive('Description');
    }

    public function getInvoice(): ?string
    {
        return $this->getCaseInsensitive('Invoice');
    }

    public function getOrderNumber(): ?string
    {
        return $this->getCaseInsensitive('Order');
    }

    public function getMutationType()
    {
        return $this->getCaseInsensitive('MutationType');
    }

    public function getSubCodeMessage(): ?string
    {
        return $this->getDeep('Status.SubCode.Description');
    }

    public function hasRedirect(): bool
    {
        return $this->getDeep('RequiredAction.RedirectURL')
            && $this->getDeep('RequiredAction.Name') == 'Redirect';
    }

    public function getRedirectUrl(): string
    {
        return $this->getDeep('RequiredAction.RedirectURL');
    }

    public function getTransactionMethod()
    {
        return $this->getCaseInsensitive('ServiceCode');
    }

    public function getTransactionType()
    {
        return $this->getCaseInsensitive('TransactionType');
    }

    public function getTransactionKey(): ?string
    {
        return $this->getCaseInsensitive('Key');
    }

    public function getDataRequest(): ?string
    {
        return $this->getCaseInsensitive('Key');
    }

    public function getPaymentMethod(): ?string
    {
        return $this->getService('PaymentMethod') ?? $this->getCaseInsensitive('ServiceCode');
    }

    public function getService($name)
    {
        return collect($this->getCaseInsensitive('Services'))->firstWhere('Name', $name);
    }

    public function getRelatedTransactionPartialPayment(): ?string
    {
        return $this->getRelatedTransactions('partialpayment');
    }

    protected function getRelatedTransactions($type = 'refund')
    {
        return collect($this->getDeep('RelatedTransactions'))
            ->firstWhere('RelationType', $type)['RelatedTransactionKey'] ?? null;
    }

    public function isRefund(): bool
    {
        return $this->getRelatedTransactions() !== null;
    }

    public function isSuccess(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS;
    }

    public function getStatusCode(): ?int
    {
        return $this->getDeep('Status.Code.Code');
    }

    public function isPendingProcessing(): bool
    {
        return BuckarooTransactionStatus::fromTransactionStatus($this->getStatusCode()) == BuckarooTransactionStatus::STATUS_PENDING ||
            in_array($this->getSubStatusCode(), ['P190', 'P191']);
    }

    public function getSubStatusCode(): ?string
    {
        return $this->getDeep('Status.SubCode.Code');
    }

    public function getPayerHash(): ?string
    {
        return $this->getCaseInsensitive('PayerHash');
    }

    public function getPaymentKey(): ?string
    {
        return $this->getCaseInsensitive('PaymentKey');
    }

    public function getArrayableItems($items): array
    {
        return parent::getArrayableItems($items['Transaction'] ?? $items['DataRequest'] ?? $items);
    }

    public function getAdditionalInformation($propertyName)
    {
        return collect($this->getDeep('AdditionalParameters.List'))
            ->where('Name', $propertyName)
            ->first()['Value'] ?? null;
    }

    public function getRefundParentKey(): ?string
    {
        return $this->getRelatedTransactions();
    }

    public function getServiceParameter($name, $parameter)
    {
        return data_get(collect($this->getServiceParameters($name))->firstWhere('Name', $parameter), 'Value');
    }

    public function getServiceParameters($name)
    {
        $service = collect($this->getService($name));
        $foundKey = $service->keys()->first(fn($k) => strtolower($k) === 'parameters');
        return $foundKey ? $service->get($foundKey) : null;
    }

    public function isTest(): bool
    {
        return $this->getCaseInsensitive('IsTest');
    }

    public function isPendingApproval(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_PENDING_APPROVAL;
    }

    public function isCanceled(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER
            || $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT;
    }

    public function isAwaitingConsumer(): bool
    {
        return $this->getStatusCode() == ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER;
    }
}
