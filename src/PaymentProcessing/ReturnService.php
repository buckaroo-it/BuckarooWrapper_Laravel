<?php

namespace Buckaroo\Laravel\PaymentProcessing;

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Handlers\ResponseParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\Models\BuckarooTransaction;

class ReturnService
{
    protected BuckarooTransaction $buckarooTransaction;
    protected ResponseParserInterface $responseParser;
    protected bool $forceProcess = false;

    public function handleReturnRequest(ReplyHandlerRequest $request): BuckarooTransaction
    {
        $this->responseParser = ResponseParser::make($request->all());
        $this->buckarooTransaction = $request->getBuckarooTransaction();

        if ($this->shouldProcessTransaction()) {
            $this->processTransaction();
        }

        return $this->buckarooTransaction;
    }

    public static function make(): static
    {
        return new static();
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
