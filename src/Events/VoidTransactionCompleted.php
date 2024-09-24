<?php

namespace Buckaroo\Laravel\Events;

use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoidTransactionCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ResponseParserInterface|TransactionResponse $transaction;
    public BuckarooTransaction $buckarooTransaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BuckarooTransaction $buckarooTransaction, ResponseParserInterface|TransactionResponse $transaction)
    {
        $this->buckarooTransaction = $buckarooTransaction;
        $this->transaction = $transaction;
    }
}
