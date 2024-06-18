<?php

namespace Buckaroo\Laravel\Events;

use Buckaroo\Laravel\Contracts\CaptureSessionModel;
use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Transaction\Response\TransactionResponse;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CaptureTransactionCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public CaptureSessionModel $captureSession;
    public ResponseParserInterface|TransactionResponse $transaction;
    public BuckarooTransaction $buckarooTransaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CaptureSessionModel $captureSession, BuckarooTransaction $buckarooTransaction, ResponseParserInterface|TransactionResponse $transaction)
    {
        $this->captureSession = $captureSession;
        $this->buckarooTransaction = $buckarooTransaction;
        $this->transaction = $transaction;
    }
}
