<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Contracts;
use Buckaroo\Laravel\Events\CaptureTransactionCompleted;
use Buckaroo\Laravel\Exceptions\BuckarooClientException;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\Payload\CapturePayload;
use Buckaroo\Laravel\Models\BuckarooTransaction;

class CaptureSessionService extends BaseService
{
    protected Contracts\CaptureSessionModel $captureSession;

    public function capture(): void
    {
        $this->beginCapture();

        [$transactionResponse, $buckarooTransaction] = $this->buckarooCapture();

        event(new CaptureTransactionCompleted($this->captureSession, $buckarooTransaction, $transactionResponse));
    }

    public function beginCapture(): self
    {
        if (!isset($this->captureSession)) {
            throw BuckarooClientException::captureSessionNotSet();
        }

        $this->paymentGatewayHandler = $this->paymentMethod->getInstance();
        $this->paymentGatewayHandler->setPaymentSession($this->paymentSession);

        if (!$this->paymentGatewayHandler instanceof Contracts\Capturable) {
            throw BuckarooClientException::notCapturable();
        }

        $this->payload = CapturePayload::make($this->paymentGatewayHandler)
            ->setPaidBuckarooTransaction($this->captureSession->getAuthorizedBuckarooTransaction())
            ->toArray();

        return $this;
    }

    public function buckarooCapture(): array
    {
        $transactionResponse = Buckaroo::api()
            ->method($this->paymentGatewayHandler->getServiceCode())
            ->capture($this->payload);

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        return [$this->paymentGatewayHandler->handlePayResponse($transactionResponse), $buckarooTransaction];
    }

    public function storeBuckarooTransaction(Contracts\ResponseParserInterface $transactionResponse, array $additionalData = []): BuckarooTransaction
    {
        $authorizedTransaction = $this->captureSession->getAuthorizedBuckarooTransaction();
        $transactionClass = Buckaroo::getTransactionModelClass();

        return $transactionClass::storeFromTransactionResponse(
            $transactionResponse,
            $this->captureSession,
            array_merge(
                [
                    'order' => $authorizedTransaction->order,
                    'action' => $transactionResponse->isSuccess() ? 'pay' : $authorizedTransaction->service_action,
                ],
                $additionalData
            )
        );
    }

    public function setCaptureSession(Contracts\CaptureSessionModel $captureSession): self
    {
        $this->captureSession = $captureSession;

        return $this;
    }
}
