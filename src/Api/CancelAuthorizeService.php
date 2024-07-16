<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Events\VoidTransactionCompleted;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

class CancelAuthorizeService extends BaseService
{
    public function void(): array
    {
        [$transactionResponse, $buckarooTransaction] = $this->buckarooVoid();

        event(new VoidTransactionCompleted($buckarooTransaction, $transactionResponse));

        return [$transactionResponse, $buckarooTransaction];
    }

    public function buckarooVoid(): array
    {
        /* @var TransactionResponse $transactionResponse */
        $transactionResponse = Buckaroo::api()
            ->method($this->paymentGateway->getServiceCode())
            ->cancelAuthorize($this->paymentGateway->toArray());

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        return [$transactionResponse, $buckarooTransaction];
    }

    public function storeBuckarooTransaction(\Buckaroo\Laravel\Handlers\ResponseParserInterface $transactionResponse, array $additionalData = []): BuckarooTransaction
    {
        return parent::storeBuckarooTransaction($transactionResponse, [
            'related_transaction_key' => $transactionResponse->getRelatedTransactionPartialPayment(),
            'service_action' => 'cancelAuthorize',
            'amount' => $transactionResponse->getAmountCredit(),
            ...$additionalData,
        ]);
    }
}
