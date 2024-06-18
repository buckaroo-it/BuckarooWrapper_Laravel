<?php

namespace Buckaroo\Laravel\PaymentMethods\NoService;

use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;

class NoService extends PaymentGatewayHandler
{
    public function getServiceCode(): ?string
    {
        return 'noservice';
    }

    public function getPayload(): array
    {
        return [
            'servicesSelectableByClient' => ($methods = collect(Buckaroo::getActivePaymentMethods()))
                ->map(fn($instance) => $instance->serviceCode)
                ->push(
                    ...$methods->firstWhere('serviceCode', 'cards')?->getConfig('enabled_cards', []),
                    ...$methods->firstWhere('serviceCode', 'giftcard')?->getConfig('enabled_cards', [])
                )
                ->join(','),
            'continueOnIncomplete' => '1',
        ];
    }
}
