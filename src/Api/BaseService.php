<?php

namespace Buckaroo\Laravel\Api;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\PaymentGatewayHandler;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;

abstract class BaseService
{
    protected PaymentGatewayHandler $paymentGateway;

    public function __construct(PaymentGatewayHandler $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public static function make(PaymentGatewayHandler $paymentGateway): static
    {
        return new static($paymentGateway);
    }

    public function storeBuckarooTransaction(ResponseParserInterface $transactionResponse, array $additionalData = []): BuckarooTransaction
    {
        return Buckaroo::getTransactionModelClass()::create([
            'payment_method' => $transactionResponse->getPaymentMethod(),
            'transaction_key' => $transactionResponse->getTransactionKey(),
            'status_code' => $transactionResponse->getStatusCode(),
            'status_subcode' => $transactionResponse->getSubStatusCode(),
            'status_subcode_description' => $transactionResponse->getSubCodeMessage(),
            'order' => $transactionResponse->get('Order'),
            'invoice' => $transactionResponse->getInvoice(),
            'is_test' => $transactionResponse->isTest(),
            'currency' => $transactionResponse->getCurrency(),
            'status' => BuckarooTransactionStatus::fromTransactionStatus($transactionResponse->getStatusCode()),
            ...$additionalData,
        ]);
    }
}
