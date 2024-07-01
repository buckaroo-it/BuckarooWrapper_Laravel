<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Contracts;
use Buckaroo\Laravel\DTO\PaymentMethod as PaymentMethodDTO;
use Buckaroo\Laravel\DTO\RefundSession as RefundSessionDTO;
use Buckaroo\Laravel\Exceptions\BuckarooClientException;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\Payload\RefundPayload;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Buckaroo\Transaction\Response\TransactionResponse;
use Exception;

class RefundSessionService extends BaseService
{
    protected Contracts\RefundSessionModel $refundSession;
    protected RefundSessionDTO $refundSessionDto;

    public function refund(): array
    {
        if (!isset($this->refundSession)) {
            throw BuckarooClientException::refundSessionNotSet();
        }

        $this->refundSessionDto = $this->refundSession->toDto();
        $refundTransactions = $this->calculateRefundTransactions();
        $transactionResponses = [];

        foreach ($refundTransactions as $refundData) {
            $transactionResponses[] = $this->processSingleRefund($refundData);
        }

        return $transactionResponses;
    }

    protected function calculateRefundTransactions(): array
    {
        $refundableTransactions = [];
        $paidTransactions = $this->paymentSession
            ->buckarooTransactions()
            ->with(['refunds' => fn ($query) => $query->refunded()])
            ->paid()
            ->get();

        $capturedTransactions = $this->paymentSession->captures() == null ?
            [] :
            $this->paymentSession
                ->captures()
                ->with(['buckarooTransactions.refunds' => fn ($query) => $query->refunded()])
                ->get()
                ->pluck('buckarooTransactions')
                ->flatten();

        $totalRefundAmount = $this->refundSessionDto->amount;
        $transactions = $paidTransactions
            ->merge($capturedTransactions)
            ->sortBy(function (BuckarooTransaction $buckarooTransaction) {
                // place the giftcards at the end of the list,
                // since the refund needs to be processed through the attached methods first
                return collect(Buckaroo::getActivePaymentMethods())
                    ->first(
                        fn (PaymentMethodDTO $paymentMethod) => $paymentMethod->serviceCode == 'giftcard' &&
                            in_array($buckarooTransaction->payment_method_id, $paymentMethod->getConfig('enabled_cards', []))
                    );
            })
            ->values();

        foreach ($transactions as $transaction) {

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

    protected function processSingleRefund(array $refundData): TransactionResponse
    {
        $transaction = $refundData['transaction'];
        $amountToRefund = $refundData['amount'];

        $this->refundSessionDto->amount = $amountToRefund;
        $paymentGatewayHandler = $this->resolvePaymentGatewayHandler($transaction);

        $payload = RefundPayload::make($paymentGatewayHandler)
            ->setRefundSessionDTO($this->refundSessionDto)
            ->setPaidBuckarooTransaction($transaction)
            ->toArray();

        $transactionResponse = Buckaroo::api()
            ->method($paymentGatewayHandler->getServiceCode())
            ->{$paymentGatewayHandler->getRefundAction()}($payload);

        $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()), $transaction);

        if ($transactionResponse->isFailed()) {
            throw new Exception($transactionResponse->getSubCodeMessage());
        }

        return $transactionResponse;
    }

    protected function resolvePaymentGatewayHandler($transaction): PaymentGatewayHandler
    {
        $paymentMethodDTO = $transaction->getPaymentMethodDTO();

        return (
            $paymentMethodDTO->parent ?
                $paymentMethodDTO->parent->getInstance() :
                $paymentMethodDTO->getInstance()
        )
            ->setPaymentSession($this->paymentSession)
            ->setRefundSession($this->refundSession);
    }

    public function setRefundSession(Contracts\RefundSessionModel $refundSession): self
    {
        $this->refundSession = $refundSession;

        return $this;
    }

    protected function storeBuckarooTransaction(Contracts\ResponseParserInterface $transactionResponse, BuckarooTransaction $paidBuckarooTransaction, array $additionalData = []): BuckarooTransaction
    {
        return $this->refundSession->createTxnFromResponse(
            $transactionResponse,
            array_merge(
                [
                    'payment_method_id' => $transactionResponse->getPaymentMethod() ?? $paidBuckarooTransaction->payment_method_id,
                    'related_transaction_key' => $transactionResponse->getRefundParentKey(),
                    'order' => $additionalData['order'] ?? $transactionResponse->get('Order') ?? $transactionResponse->getInvoice(),
                    'amount' => $transactionResponse->getAmountCredit() * -1,
                    'action' => 'refund',
                ],
                $additionalData
            )
        );
    }
}
