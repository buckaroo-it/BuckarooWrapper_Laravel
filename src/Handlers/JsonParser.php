<?php

namespace Buckaroo\Laravel\Handlers;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Resources\Constants\ResponseStatus;
use LogicException;

class JsonParser extends ResponseParser
{
    public function getAmountDebit(): ?float
    {
        return $this->formatAmount($this->get('AmountDebit'));
    }

    public function getAmountCredit(): ?float
    {
        return $this->formatAmount($this->get('AmountCredit'));
    }

    public function getAmount(): ?float
    {
        return $this->formatAmount($this->get('Amount'));
    }

    public function getCurrency(): ?string
    {
        return $this->get('Currency');
    }

    public function getCustomerName(): ?string
    {
        return $this->get('CustomerName');
    }

    public function getDescription()
    {
        return $this->get('Description');
    }

    public function getInvoice(): ?string
    {
        return $this->get('Invoice');
    }

    public function getOrderNumber(): ?string
    {
        return $this->get('Order');
    }

    public function getMutationType()
    {
        return $this->get('MutationType');
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

    public function getTransactionMethod()
    {
        return $this->get('ServiceCode');
    }

    public function getTransactionType()
    {
        return $this->get('TransactionType');
    }

    public function getTransactionKey(): ?string
    {
        return $this->get('Key');
    }

    public function getPaymentMethod(): ?string
    {
        return $this->getService('PaymentMethod');
    }

    public function getService($name)
    {
        return collect($this->get('Services'))->firstWhere('Name', $name);
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
        return $this->get('PayerHash');
    }

    public function getPaymentKey(): ?string
    {
        return $this->get('PaymentKey');
    }

    public function getData(): array
    {
        $key = $this->items['Transaction'] ?? $this->items['DataRequest'] ?? null;

        if (!$key) {
            throw new LogicException('Neither `Transaction` nor `DataRequest` provided', 1);
        }

        if (!is_array(data_get($this->items, $key))) {
            throw new LogicException("Invalid value for `{$key}`", 1);
        }

        return data_get($this->items, $key);
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
        return collect($this->getService($name))->get('Parameters');
    }

    public function isTest(): bool
    {
        return $this->get('IsTest');
    }
}
