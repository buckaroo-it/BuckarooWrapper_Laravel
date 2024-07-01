<?php

namespace Buckaroo\Laravel\PaymentProcessing;

use Buckaroo\Laravel\Api\PaymentSessionService;
use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\DTO\PaymentMethod as PaymentMethodDTO;
use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Buckaroo\Laravel\Handlers\ResponseParser;
use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Resources\Constants\ResponseStatus;

class PushService
{
    protected PaymentSessionService $paymentSessionService;
    protected BuckarooTransaction $buckarooTransaction;
    protected ResponseParserInterface $responseParser;

    public function __construct(PaymentSessionService $paymentSessionService)
    {
        $this->paymentSessionService = $paymentSessionService;
    }

    public function handlePushRequest(ReplyHandlerRequest $request): array
    {
        $this->responseParser = ResponseParser::make($request->all());
        $this->buckarooTransaction = $request->getBuckarooTransaction();

        if ($this->buckarooTransaction->hasServiceAction('pay')) {
            $this->handlePayAction();
        } elseif ($this->buckarooTransaction->hasServiceAction('refund')) {
            $this->handleRefundAction();
        }

        return ['status' => true];
    }

    private function handlePayAction()
    {
        $paymentMethodDTO = $this->buckarooTransaction->getPaymentMethodDTO();

        if ($this->buckarooTransaction->transaction_key == $this->responseParser->getTransactionKey()) {
            $this->updateTransaction();
            $this->processPaymentSession($paymentMethodDTO);
        } elseif ($relatedTransactionKey = $this->responseParser->getRelatedTransactionPartialPayment()) {
            $this->handleRelatedTransaction($paymentMethodDTO, $relatedTransactionKey);
        }

        if ($this->responseParser->getStatusCode() != ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER) {
            event(new PayTransactionCompleted($this->buckarooTransaction->payable, $this->buckarooTransaction, $this->responseParser));
        }
    }

    private function updateTransaction(array $additionalData = [])
    {
        $paymentMethodId = $this->responseParser->existingPaymentMethod($this->buckarooTransaction->payment_method_id);

        return $this->buckarooTransaction->update([
            'payment_method_id' => $paymentMethodId,
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

    private function processPaymentSession(PaymentMethodDTO $paymentMethodDTO)
    {
        $this->paymentSessionService
            ->setPaymentMethod($paymentMethodDTO)
            ->setPaymentSession($this->buckarooTransaction->payable)
            ->beginPay()
            ->checkForAuthorization($this->responseParser);
    }

    private function handleRelatedTransaction(PaymentMethodDTO $paymentMethodDTO, string $relatedTransactionKey)
    {
        if ($paymentMethodDTO) {
            $this->paymentSessionService->setPaymentMethod($paymentMethodDTO);
        }

        if (
            $this->buckarooTransaction->payable
                ->buckarooTransactions()
                ->whereRelatedTransactionKey($relatedTransactionKey)
                ->whereTransactionKey($this->responseParser->getTransactionKey())
                ->doesntExist()
        ) {
            $this->paymentSessionService
                ->setPaymentSession($this->buckarooTransaction->payable)
                ->storeBuckarooTransaction($this->responseParser, [
                    'action' => "push/{$this->buckarooTransaction->service_action}",
                    'order' => $this->buckarooTransaction->order,
                ]);
        }
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

        event(new RefundTransactionCompleted($this->buckarooTransaction->payable, $this->buckarooTransaction, $this->responseParser));
    }
}
