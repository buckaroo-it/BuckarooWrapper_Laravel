<?php

namespace Buckaroo\Laravel\Handlers\Payload;

use Buckaroo\Laravel\Contracts\Capturable;
use Buckaroo\Laravel\Exceptions\BuckarooClientException;

class VoidPayload extends DefaultPayload
{
    public function toArray(): array
    {
        if (!$this->payable instanceof Capturable) {
            throw BuckarooClientException::notCapturable();
        }

        return array_merge(
            [
                // 'description' => $this->getTransactionDescription(),
                'invoice' => $this->paidBuckarooTransaction->invoice,
                'amountCredit' => $this->paidBuckarooTransaction->amount,
                'currency' => $this->paidBuckarooTransaction->currency,
                'originalTransactionKey' => $this->paidBuckarooTransaction->transaction_key,
            ],
            $this->payable->getCapturePayload() ?? [],
        );
    }
}
