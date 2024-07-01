<?php

namespace Buckaroo\Laravel\Handlers\Payload;

use Buckaroo\Laravel\Contracts\PayableInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Resources\Arrayable;

abstract class DefaultPayload implements Arrayable
{
    protected PayableInterface $payable;
    protected BuckarooTransaction $paidBuckarooTransaction;

    public function __construct(PayableInterface $payable)
    {
        $this->payable = $payable;
    }

    public static function make(PayableInterface $payable): static
    {
        return new static($payable);
    }

    public function setPaidBuckarooTransaction(BuckarooTransaction $paidBuckarooTransaction): self
    {
        $this->paidBuckarooTransaction = $paidBuckarooTransaction;

        return $this;
    }
}
