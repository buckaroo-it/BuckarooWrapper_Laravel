<?php

namespace Buckaroo\Laravel\Handlers\Payload;

class PayPayload extends DefaultPayload
{
    public function toArray(): array
    {
        $paymentSession = $this->payable->getPaymentSession();

        return array_merge(
            [
                'currency' => $paymentSession->currency,
                'amountDebit' => $paymentSession->amount,
                // 'description' => $this->getTransactionDescription(),
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
