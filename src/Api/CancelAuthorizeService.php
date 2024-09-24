<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Events\VoidTransactionCompleted;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

class CancelAuthorizeService extends BaseService
{
    public function void(): array
    {
        $transactionResponse = $this->buckarooCancelAuthorize();

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        event(new VoidTransactionCompleted($buckarooTransaction, $transactionResponse));

        return [$transactionResponse, $buckarooTransaction];
    }

    public function buckarooCancelAuthorize(): TransactionResponse
    {
        return Buckaroo::api()
            ->method($this->paymentGateway->getServiceCode())
            ->cancelAuthorize($this->paymentGateway->toArray());
    }

    public function storeBuckarooTransaction(ResponseParserInterface $transactionResponse, array $additionalData = []): BuckarooTransaction
    {
        return parent::storeBuckarooTransaction($transactionResponse, [
            'related_transaction_key' => $transactionResponse->getRelatedTransactionPartialPayment(),
            'service_action' => 'cancelAuthorize',
            'amount' => $transactionResponse->getAmountCredit(),
            ...$additionalData,
        ]);
    }
}
