<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Contracts;
use Buckaroo\Laravel\Events\VoidTransactionCompleted;
use Buckaroo\Laravel\Exceptions\BuckarooClientException;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\Payload\VoidPayload;
use Buckaroo\Laravel\Models\BuckarooTransaction;

class VoidSessionService extends BaseService
{
    protected Contracts\VoidSessionModel $voidSession;

    public function void(): void
    {
        $this->beginVoid();

        [$transactionResponse, $buckarooTransaction] = $this->buckarooVoid();

        event(new VoidTransactionCompleted($this->voidSession, $buckarooTransaction, $transactionResponse));
    }

    public function beginVoid(): static
    {
        if (!isset($this->voidSession)) {
            throw BuckarooClientException::voidSessionNotSet();
        }

        $this->paymentGatewayHandler = $this->paymentMethod->getInstance();
        $this->paymentGatewayHandler->setPaymentSession($this->paymentSession);

        if (!$this->paymentGatewayHandler instanceof Contracts\Capturable) {
            throw BuckarooClientException::notCapturable();
        }

        $this->payload = VoidPayload::make($this->paymentGatewayHandler)
            ->setPaidBuckarooTransaction($this->voidSession->getAuthorizedBuckarooTransaction())
            ->toArray();

        return $this;
    }

    public function buckarooVoid(): array
    {
        $transactionResponse = Buckaroo::api()
            ->method($this->paymentGatewayHandler->getServiceCode())
            ->cancelAuthorize($this->payload);

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        return [$this->paymentGatewayHandler->handlePayResponse($transactionResponse), $buckarooTransaction];
    }

    public function storeBuckarooTransaction(Contracts\ResponseParserInterface $transactionResponse, array $additionalData = []): BuckarooTransaction
    {
        $transactionClass = Buckaroo::getTransactionModelClass();

        return $transactionClass::storeFromTransactionResponse(
            $transactionResponse,
            $this->voidSession,
            array_merge(
                [
                    'order' => $this->voidSession->getAuthorizedBuckarooTransaction()->order,
                    'amount' => $transactionResponse->getAmountCredit(),
                    'action' => $additionalData['action'] ?? $this->paymentGatewayHandler->getPayAction(),
                ],
                $additionalData
            )
        );
    }

    public function setVoidSession(Contracts\VoidSessionModel $voidSession): self
    {
        $this->voidSession = $voidSession;

        return $this;
    }
}
