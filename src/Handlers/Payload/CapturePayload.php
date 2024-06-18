<?php

namespace Buckaroo\Laravel\Handlers\Payload;

use Buckaroo\Laravel\Contracts\Capturable;
use Buckaroo\Laravel\Exceptions\BuckarooClientException;

class CapturePayload extends DefaultPayload
{
    public function toArray(): array
    {
        if (!$this->payable instanceof Capturable) {
            throw BuckarooClientException::notCapturable();
        }

        return array_merge(
            [
                'invoice' => $this->paidBuckarooTransaction->invoice,
                'amountDebit' => $this->paidBuckarooTransaction->amount,
                'currency' => $this->paidBuckarooTransaction->currency,
                'originalTransactionKey' => $this->paidBuckarooTransaction->transaction_key,
                'description' => $this->payable->getPaymentSession()->getTransactionDescription(),
            ],
            $this->payable->getCapturePayload() ?? [],
        );
    }
}
