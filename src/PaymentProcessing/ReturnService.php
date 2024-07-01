<?php

namespace Buckaroo\Laravel\PaymentProcessing;

use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Handlers\ResponseParser;
use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\Models\BuckarooTransaction;

class ReturnService
{
    protected BuckarooTransaction $buckarooTransaction;
    protected ResponseParserInterface $responseParser;
    protected bool $forceProcess = false;

    public function handleReturnRequest(ReplyHandlerRequest $request): string
    {
        $this->responseParser = ResponseParser::make($request->all());
        $this->buckarooTransaction = $request->getBuckarooTransaction();

        if ($this->shouldProcessTransaction()) {
            $this->processTransaction();
        }

        return $this->buckarooTransaction->payable->cancel_url;
    }

    public function forceProcess(bool $force = true): self
    {
        $this->forceProcess = $force;

        return $this;
    }

    protected function shouldProcessTransaction(): bool
    {
        return $this->forceProcess || ($this->responseParser->isPendingProcessing() && !$this->buckarooTransaction->isPushAction());
    }

    protected function processTransaction(): void
    {
        event(new PayTransactionCompleted(
            $this->buckarooTransaction->payable,
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
}
