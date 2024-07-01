<?php

namespace Buckaroo\Laravel\Handlers\Payload;

class PayPayload extends DefaultPayload
{
    public function toArray(): array
    {
        $paymentSession = $this->payable->getPaymentSession()->toDto();

        return array_merge(
            [
                'currency' => $paymentSession->currency,
                'amountDebit' => $paymentSession->amount,
                'description' => $this->payable->getPaymentSession()->getTransactionDescription(),
                'order' => $paymentSession->order,
                'invoice' => $paymentSession->invoice,
                'returnURL' => route('buckaroo.return'),
                'pushURL' => route('buckaroo.push'),
                'clientIP' => request()->ip(),
            ],
            $this->payable->getPayload() ?? [],
        );
    }
}
