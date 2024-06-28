<?php

namespace Buckaroo\Laravel\PaymentMethods\GiftCard;

use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Laravel\PaymentMethods\PaymentGatewayHandler;
use Exception;

class GiftCard extends PaymentGatewayHandler
{
    public function getRefundPayload(BuckarooTransaction $buckarooTransaction): array
    {
        $customerDTO = $buckarooTransaction->payable->toDto()->customer;

        $payload = collect([
            'name' => $buckarooTransaction->payment_method_id,
            'email' => $customerDTO->email,
            'lastname' => $customerDTO->billingAddress->lastName,
        ]);

        if (in_array($buckarooTransaction->payment_method_id, ['fashioncheque'])) {
            $payload = $payload->forget(['email', 'lastname']);
        }

        return $payload->toArray();
    }

    public function getPayload(): array
    {
        $activeGiftcards = $this->getOption('enabled_cards', []);
        $activePaymentMethods = collect(Buckaroo::getActivePaymentMethods())
            ->filter(fn ($instance) => !$instance->getInstance() instanceof $this)
            ->map(fn ($instance) => $instance->serviceCode)
            ->values();

        if (blank($activeGiftcards)) {
            throw new Exception('No active giftcards found');
        }

        return [
            'servicesSelectableByClient' => $activePaymentMethods->merge($activeGiftcards)->join(','),
            'continueOnIncomplete' => '1',
        ];
    }

    public function getServiceCode(): ?string
    {
        return isset($this->refundSession) ? 'giftcard' : 'noservice';
    }
}
