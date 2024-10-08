<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Events\CaptureTransactionCompleted;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

class CaptureService extends BaseService
{
    public function capture(): array
    {
        $transactionResponse = $this->buckarooCapture();

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        event(new CaptureTransactionCompleted($buckarooTransaction, $transactionResponse));

        return [$transactionResponse, $buckarooTransaction];
    }

    public function buckarooCapture(): TransactionResponse
    {
        return Buckaroo::api()
            ->method($this->paymentGateway->getServiceCode())
            ->capture($this->paymentGateway->toArray());
    }

    public function storeBuckarooTransaction(ResponseParserInterface $transactionResponse, array $additionalData = []): BuckarooTransaction
    {
        return parent::storeBuckarooTransaction($transactionResponse, [
            'related_transaction_key' => $transactionResponse->getRelatedTransactionPartialPayment(),
            'service_action' => $this->paymentGateway->getPayAction(),
            'amount' => $transactionResponse->getAmount() ?? $transactionResponse->getAmountDebit(),
            ...$additionalData,
        ]);
    }
}
