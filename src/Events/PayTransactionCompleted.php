<?php

namespace Buckaroo\Laravel\Events;

use Buckaroo\Laravel\Contracts\PaymentSessionModel;
use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PayTransactionCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PaymentSessionModel $paymentSession;
    public ResponseParserInterface|TransactionResponse $transaction;
    public BuckarooTransaction $buckarooTransaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PaymentSessionModel $paymentSession, BuckarooTransaction $buckarooTransaction, ResponseParserInterface|TransactionResponse $transaction)
    {
        $this->paymentSession = $paymentSession;
        $this->buckarooTransaction = $buckarooTransaction;
        $this->transaction = $transaction;
    }
}
