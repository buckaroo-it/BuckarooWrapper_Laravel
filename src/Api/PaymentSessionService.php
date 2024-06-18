<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Contracts;
use Buckaroo\Laravel\DTO\PaymentMethod as PaymentMethodDTO;
use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Exceptions\BuckarooClientException;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\JsonParser;
use Buckaroo\Laravel\Handlers\Payload\PayPayload;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;

class PaymentSessionService extends BaseService
{
    public function __construct()
    {
        $this->paymentMethod = new PaymentMethodDTO(serviceCode: 'noservice');
    }

    public function pay(): ?TransactionResponse
    {
        $this->beginPay();

        if ($this->paymentGatewayHandler instanceof Contracts\HasCustomizedCheckoutView) {
            $checkoutViewCheck = $this->paymentGatewayHandler->checkoutView($this->payload);

            if (isset($checkoutViewCheck['redirect_url'])) {
                return $this->createRedirectTransactionResponse($checkoutViewCheck['redirect_url']);
            }
        }

        [$transactionResponse, $buckarooTransaction] = $this->buckarooPay();

        if (!$transactionResponse->hasRedirect()) {
            event(new PayTransactionCompleted($this->paymentSession, $buckarooTransaction, $transactionResponse));
        }

        return $transactionResponse;
    }

    public function beginPay(): self
    {
        $this->paymentGatewayHandler = $this->paymentMethod->getInstance();

        if (!$this->paymentGatewayHandler instanceof Contracts\PayableInterface) {
            throw BuckarooClientException::notPayable();
        }

        $this->paymentGatewayHandler->setPaymentSession($this->paymentSession);
        $this->payload = PayPayload::make($this->paymentGatewayHandler)->toArray();

        return $this;
    }

    protected function createRedirectTransactionResponse(string $redirectUrl): TransactionResponse
    {
        return new TransactionResponse([], ['RequiredAction' => ['RedirectURL' => $redirectUrl, 'Name' => 'Redirect']]);
    }

    public function buckarooPay(): array
    {
        /* @var TransactionResponse $transactionResponse */
        $transactionResponse = Buckaroo::api()
            ->method($this->paymentGatewayHandler->getServiceCode())
            ->{$this->paymentGatewayHandler->getPayAction()}($this->payload);

        $buckarooTransaction = $this->storeBuckarooTransaction(JsonParser::make($transactionResponse->toArray()));

        return [$this->paymentGatewayHandler->handlePayResponse($transactionResponse), $buckarooTransaction];
    }

    public function storeBuckarooTransaction(Contracts\ResponseParserInterface $transactionResponse, array $additionalData = []): BuckarooTransaction
    {
        $transactionClass = Buckaroo::getTransactionModelClass();

        return $transactionClass::storeFromTransactionResponse(
            $transactionResponse,
            $this->paymentSession,
            array_merge(
                [
                    'order' => $additionalData['order'] ?? $transactionResponse->get('Order'),
                    'action' => $additionalData['action'] ?? $this->paymentGatewayHandler->getPayAction(),
                ],
                $additionalData
            )
        );
    }

    public function checkForAuthorization(Contracts\ResponseParserInterface $transactionResponse): static
    {
        $shouldAuthorize = $this->paymentGatewayHandler instanceof Contracts\Capturable && $transactionResponse->isSuccess() && $this->paymentGatewayHandler->getPayAction() == 'authorize';

        if ($shouldAuthorize) {
            $this->paymentSession->setConfig('is_authorized', true);
        }

        return $this;
    }
}
