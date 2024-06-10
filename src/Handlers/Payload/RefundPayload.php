<?php

namespace Buckaroo\Laravel\Handlers\Payload;

class RefundPayload extends DefaultPayload
{
    public function toArray(): array
    {
        $refundSession = $this->payable->getRefundSession();

        return array_merge(
            [
                'currency' => $refundSession->currency,
                // 'description' => $this->getRefundDescription(),
                'invoice' => $this->paidBuckarooTransaction->invoice,
                'originalTransactionKey' => $this->paidBuckarooTransaction->transaction_key,
                'amountCredit' => $refundSession->amount,
                'pushURL' => route('buckaroo.push'),
            ],
            $this->payable->getRefundPayload($this->paidBuckarooTransaction) ?? [],
        );
    }
}
