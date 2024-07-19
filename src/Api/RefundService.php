<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

class RefundService extends BaseService
{
    public function refund(): array
    {
        $transactionResponse = $this->buckarooRefund();

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        event(new RefundTransactionCompleted(
            $buckarooTransaction,
            $transactionResponse
        ));

        return [$transactionResponse, $buckarooTransaction];
    }

    public function buckarooRefund(): TransactionResponse
    {
        return Buckaroo::api()
            ->method($this->paymentGateway->getServiceCode())
            ->{$this->paymentGateway->getRefundAction()}($this->paymentGateway->toArray());
    }

    public function storeBuckarooTransaction(ResponseParserInterface $transactionResponse, array $additionalData = []): BuckarooTransaction
    {
        return parent::storeBuckarooTransaction($transactionResponse, [
            'related_transaction_key' => $transactionResponse->getRefundParentKey(),
            'service_action' => $this->paymentGateway->getRefundAction(),
            'amount' => $transactionResponse->getAmountCredit() * -1,
            ...$additionalData,
        ]);
    }
}
