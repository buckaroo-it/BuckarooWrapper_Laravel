<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;
use Exception;
use Illuminate\Support\Collection;

class RefundService extends BaseService
{
    protected Collection $paidTransactions;

    public function setPaidTransactions(Collection $paidTransactions): RefundService
    {
        $this->paidTransactions = $paidTransactions;

        return $this;
    }

    public function refund(): array
    {
        $refundTransactions = $this->calculateRefundTransactions();
        $transactionResponses = [];

        foreach ($refundTransactions as $refundData) {
            $transactionResponses[] = $this->processSingleRefund($refundData);
        }

        return $transactionResponses;
    }

    protected function calculateRefundTransactions(): array
    {
        $totalRefundAmount = round($this->paymentGateway->getAmountCredit(), 2);
        $refundableTransactions = [];

        foreach ($this->paidTransactions as $transaction) {
            $refundedAmount = abs($transaction->refunds->sum('amount'));
            $availableToRefund = round($transaction->amount - $refundedAmount, 2);

            if ($totalRefundAmount <= 0) {
                break;
            }

            $amountToRefund = min($totalRefundAmount, $availableToRefund);
            $totalRefundAmount -= $amountToRefund;

            if ($amountToRefund > 0) {
                $refundableTransactions[] = [
                    'transaction' => $transaction,
                    'amount' => $amountToRefund,
                ];
            }
        }

        return $refundableTransactions;
    }

    protected function processSingleRefund(array $refundData): array
    {
        $transaction = $refundData['transaction'];
        $amountToRefund = $refundData['amount'];

        $this->paymentGateway = $transaction->getPaymentGateway()->setPayload($this->paymentGateway->toArray());

        $this->paymentGateway->setAmountCredit($amountToRefund);
        $this->paymentGateway->setOriginalTransactionKey($transaction->transaction_key);

        $transactionResponse = $this->buckarooRefund($transaction);

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        if ($transactionResponse->isFailed()) {
            throw new Exception($transactionResponse->getSubCodeMessage());
        }

        return [$buckarooTransaction, $transactionResponse];
    }

    public function buckarooRefund($transaction): TransactionResponse
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
