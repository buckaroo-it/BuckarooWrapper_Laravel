<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

class PayService extends BaseService
{
    public function pay(): array
    {
        $transactionResponse = $this->buckarooPay();

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        if (!$transactionResponse->hasRedirect()) {
            event(new PayTransactionCompleted(
                $buckarooTransaction,
                $transactionResponse
            ));
        }

        return [$transactionResponse, $buckarooTransaction];
    }

    public function buckarooPay(): TransactionResponse
    {
        return Buckaroo::api()
            ->method($this->paymentGateway->getServiceCode())
            ->{$this->paymentGateway->getPayAction()}($this->paymentGateway->toArray());
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
