<?php

namespace Buckaroo\Laravel\Events;

use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\Contracts\VoidSessionModel;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoidTransactionCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public VoidSessionModel $voidSession;
    public ResponseParserInterface|TransactionResponse $transaction;
    public BuckarooTransaction $buckarooTransaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(VoidSessionModel $voidSession, BuckarooTransaction $buckarooTransaction, ResponseParserInterface|TransactionResponse $transaction)
    {
        $this->voidSession = $voidSession;
        $this->buckarooTransaction = $buckarooTransaction;
        $this->transaction = $transaction;
    }
}
