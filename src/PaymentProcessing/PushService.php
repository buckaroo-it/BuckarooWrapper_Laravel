<?php

namespace Buckaroo\Laravel\PaymentProcessing;

use Buckaroo\Laravel\Api\PayService;
use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\ResponseParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Resources\Constants\ResponseStatus;

class PushService
{
    protected PayService $paymentSessionService;
    protected BuckarooTransaction $buckarooTransaction;
    protected ResponseParserInterface $responseParser;

    public function __construct(PayService $paymentSessionService)
    {
        $this->paymentSessionService = $paymentSessionService;
    }

    public function handlePushRequest(ReplyHandlerRequest $request): array
    {
        $this->responseParser = ResponseParser::make($request->all());
        $this->buckarooTransaction = $request->getBuckarooTransaction();

        if ($this->buckarooTransaction->hasServiceAction('pay') || $this->buckarooTransaction->hasServiceAction('authorize')) {
            $this->handlePayAction();
        } elseif ($this->buckarooTransaction->hasServiceAction('refund')) {
            $this->handleRefundAction();
        }

        return ['status' => true];
    }

    public static function make(): static
    {
        return new static(app(PayService::class));
    }

    private function handlePayAction()
    {
        if ($this->buckarooTransaction->transaction_key == $this->responseParser->getTransactionKey()) {
            $this->updateTransaction();
        } elseif ($this->responseParser->getRelatedTransactionPartialPayment() && $this->relatedTransactionDoesntExists()) {
            $this->handleRelatedTransaction();
        }

        if ($this->responseParser->getStatusCode() != ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER) {
            event(new PayTransactionCompleted(
                $this->buckarooTransaction,
                $this->responseParser
            ));
        }
    }

    protected function updateTransaction(array $additionalData = [])
    {
        return $this->buckarooTransaction->update([
            'payment_method_id' => $this->responseParser->getPaymentMethod(),
            'amount' => $this->responseParser->getAmount(),
            'status_code' => $this->responseParser->getStatusCode(),
            'status_subcode' => $this->responseParser->getSubStatusCode(),
            'status_subcode_description' => $this->responseParser->getSubCodeMessage(),
            'status' => BuckarooTransactionStatus::fromTransactionStatus($this->responseParser->getStatusCode()),
            'service_action' => "push/{$this->buckarooTransaction->service_action}",
            'related_transaction_key' => $this->responseParser->getRelatedTransactionPartialPayment(),
            ...$additionalData,
        ]);
    }

    protected function relatedTransactionDoesntExists()
    {
        return Buckaroo::getTransactionModelClass()::query()
            ->whereRelatedTransactionKey($this->responseParser->getRelatedTransactionPartialPayment())
            ->whereTransactionKey($this->responseParser->getTransactionKey())
            ->doesntExist();
    }

    protected function handleRelatedTransaction()
    {
        $transaction = $this->paymentSessionService->storeBuckarooTransaction($this->responseParser);
        $transaction->update([
            'action' => "push/{$this->buckarooTransaction->service_action}",
            'order' => $this->buckarooTransaction->order,
        ]);

        return $transaction;
    }

    private function handleRefundAction()
    {
        $this->updateTransaction([
            'amount' => $this->responseParser->getAmountCredit() * -1,
            'related_transaction_key' => $this->responseParser->getRefundParentKey(),
        ]);

        if ($this->responseParser->isPendingProcessing()) {
            return ['status' => true];
        }

        event(new RefundTransactionCompleted($this->buckarooTransaction, $this->responseParser));
    }
}
