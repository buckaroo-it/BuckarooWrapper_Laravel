<?php

namespace Buckaroo\Laravel\PaymentMethods\CreditCard;

use Buckaroo\Laravel\Contracts\Capturable;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Exception;

class CreditCard extends PaymentGatewayHandler implements Capturable
{
    public function getServiceCode(): ?string
    {
        return isset($this->refundSession) ? 'creditcard' : 'noservice';
    }

    public function getCapturePayload(): array
    {
        return $this->getPayload();
    }

    public function getPayload(): array
    {
        $activeCards = $this->getOption('enabled_cards', []);

        if (blank($activeCards)) {
            throw new Exception('No active cards found');
        }

        return [
            'servicesSelectableByClient' => implode(',', $activeCards),
            'continueOnIncomplete' => '1',
        ];
    }

    public function getRefundPayload(BuckarooTransaction $buckarooTransaction): array
    {
        return [
            'name' => $buckarooTransaction->payment_method_id,
        ];
    }
}
