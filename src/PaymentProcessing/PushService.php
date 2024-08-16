<?php

namespace Buckaroo\Laravel\PaymentProcessing;

use Buckaroo\Laravel\Api\PayService;
use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Resources\Constants\ResponseStatus;

class PushService extends BaseService
{
    public function handlePushRequest(): array
    {
        if ($this->buckarooTransaction->hasServiceAction('pay') || $this->buckarooTransaction->hasServiceAction('authorize')) {
            $this->handlePayAction();
        } elseif ($this->buckarooTransaction->hasServiceAction('refund')) {
            $this->handleRefundAction();
        }

        return ['status' => true];
    }

    private function handlePayAction()
    {
        if ($this->buckarooTransaction->transaction_key == $this->responseParser->getTransactionKey()) {
            $this->updateTransaction();
        } elseif ($this->responseParser->getRelatedTransactionPartialPayment() && $this->relatedTransactionDoesntExists()) {
            $this->handleRelatedTransaction();
        }

        if ($this->responseParser->getStatusCode() != ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER) {
            $this->dispatchPayTransactionCompletedEvent();
        }
    }

    protected function updateTransaction(array $additionalData = [])
    {
        return $this->buckarooTransaction->update([
            'payment_method' => $this->responseParser->getPaymentMethod(),
            'amount' => $this->responseParser->getAmount(),
            'status_code' => $this->responseParser->getStatusCode(),
            'status_subcode' => $this->responseParser->getSubStatusCode(),
            'status_subcode_description' => $this->responseParser->getSubCodeMessage(),
            'status' => BuckarooTransactionStatus::fromTransactionStatus($this->responseParser->getStatusCode()),
            'service_action' => "push/{$this->buckarooTransaction->service_action}",
            'related_transaction_key' => $this->responseParser->getRelatedTransactionPartialPayment(),
            ...$additionalData,
        ]);
    }

    protected function relatedTransactionDoesntExists()
    {
        return Buckaroo::getTransactionModelClass()::query()
            ->whereRelatedTransactionKey($this->responseParser->getRelatedTransactionPartialPayment())
            ->whereTransactionKey($this->responseParser->getTransactionKey())
            ->doesntExist();
    }

    protected function handleRelatedTransaction()
    {
        return app(PayService::class)->storeBuckarooTransaction($this->responseParser, [
            'action' => "push/{$this->buckarooTransaction->service_action}",
            'order' => $this->buckarooTransaction->order,
        ]);
    }

    protected function handleRefundAction()
    {
        $this->updateTransaction([
            'amount' => $this->responseParser->getAmountCredit() * -1,
            'related_transaction_key' => $this->responseParser->getRefundParentKey(),
        ]);

        if ($this->responseParser->isPendingProcessing()) {
            return ['status' => true];
        }

        $this->dispatchRefundTransactionCompletedEvent();
    }

    protected function dispatchRefundTransactionCompletedEvent(): self
    {
        event(new RefundTransactionCompleted($this->buckarooTransaction, $this->responseParser));

        return $this;
    }

    protected function dispatchPayTransactionCompletedEvent(): self
    {
        event(new PayTransactionCompleted($this->buckarooTransaction, $this->responseParser));

        return $this;
    }
}
