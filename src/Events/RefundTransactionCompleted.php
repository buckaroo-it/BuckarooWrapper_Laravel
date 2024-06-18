<?php

namespace Buckaroo\Laravel\Events;

use Buckaroo\Laravel\Contracts\RefundSessionModel;
use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefundTransactionCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public RefundSessionModel $refundSession;
    public ResponseParserInterface|TransactionResponse $transaction;
    public BuckarooTransaction $buckarooTransaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RefundSessionModel $refundSession, BuckarooTransaction $buckarooTransaction, ResponseParserInterface|TransactionResponse $transaction)
    {
        $this->refundSession = $refundSession;
        $this->buckarooTransaction = $buckarooTransaction;
        $this->transaction = $transaction;
    }
}
