<?php

namespace Buckaroo\Laravel\Traits;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\Contracts\SessionModel;
use Buckaroo\Laravel\Facades\Buckaroo;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasBuckarooTransactions
{
    public function createTxnFromResponse(ResponseParserInterface $transactionResponse, array $additionalData = [])
    {
        if (!$this instanceof SessionModel) {
            throw new Exception('The model using the HasBuckarooTransactions trait must implement the SessionModel contract');
        }

        $paymentSessionDto = $this->toDto();

        return $this->buckarooTransactions()->create([
            'payment_method_id' => $additionalData['payment_method_id'] ?? $transactionResponse->existingPaymentMethod($paymentSessionDto->paymentMethod),
            'related_transaction_key' => $additionalData['related_transaction_key'] ?? $transactionResponse->getRelatedTransactionPartialPayment(),
            'transaction_key' => $transactionResponse->getTransactionKey(),
            'status_code' => $transactionResponse->getStatusCode(),
            'status_subcode' => $transactionResponse->getSubStatusCode(),
            'status_subcode_description' => $transactionResponse->getSubCodeMessage(),
            'order' => $additionalData['order'] ?? $transactionResponse->get('Order'),
            'invoice' => $transactionResponse->getInvoice(),
            'is_test' => $transactionResponse->isTest(),
            'currency' => $transactionResponse->getCurrency(),
            'amount' => $additionalData['amount'] ?? $transactionResponse->getAmount() ?? $transactionResponse->getAmountDebit(),
            'status' => BuckarooTransactionStatus::fromTransactionStatus($transactionResponse->getStatusCode()),
            'service_action' => $additionalData['action'],
        ]);
    }

    public function buckarooTransactions(): ?MorphMany
    {
        return $this->morphMany(Buckaroo::getTransactionModelClass(), 'payable');
    }
}
