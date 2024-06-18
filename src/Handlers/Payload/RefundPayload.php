<?php

namespace Buckaroo\Laravel\Handlers\Payload;

use Buckaroo\Laravel\DTO\RefundSession as RefundSessionDTO;

class RefundPayload extends DefaultPayload
{
    protected RefundSessionDTO $refundSessionDTO;

    public function setRefundSessionDTO(RefundSessionDTO $refundSessionDTO): self
    {
        $this->refundSessionDTO = $refundSessionDTO;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'currency' => $this->refundSessionDTO->currency,
                'description' => $this->payable->getPaymentSession()->getTransactionDescription(),
                'invoice' => $this->paidBuckarooTransaction->invoice,
                'originalTransactionKey' => $this->paidBuckarooTransaction->transaction_key,
                'amountCredit' => $this->refundSessionDTO->amount,
                'pushURL' => route('buckaroo.push'),
            ],
            $this->payable->getRefundPayload($this->paidBuckarooTransaction) ?? [],
        );
    }
}
