<?php

namespace Buckaroo\Laravel\PaymentProcessing;

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Models\BuckarooTransaction;

class ReturnService extends BaseService
{
    protected bool $forceProcess = false;

    public function handleReturnRequest(): BuckarooTransaction
    {
        if ($this->shouldProcessTransaction()) {
            $this->processTransaction();
        }

        return $this->buckarooTransaction;
    }

    protected function shouldProcessTransaction(): bool
    {
        return $this->forceProcess || ($this->responseParser->isPendingProcessing() && !$this->buckarooTransaction->isPushAction());
    }

    protected function processTransaction(): void
    {
        event(new PayTransactionCompleted(
            $this->buckarooTransaction,
            $this->responseParser
        ));

        $this->updateTransaction();
    }

    protected function updateTransaction(array $additionalData = [])
    {
        return $this->buckarooTransaction->update([
            'service_action' => "return/{$this->buckarooTransaction->service_action}",
            ...$additionalData,
        ]);
    }

    public function forceProcess(bool $force = true): self
    {
        $this->forceProcess = $force;

        return $this;
    }
}
