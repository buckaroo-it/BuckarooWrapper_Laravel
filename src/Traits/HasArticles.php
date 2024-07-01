<?php

namespace Buckaroo\Laravel\Traits;

use Arr;
use Buckaroo\Laravel\DTO\Product;

trait HasArticles
{
    protected function getArticlesPayload(): array
    {
        $paymentSessionDTO = $this->getPaymentSession()->toDto();

        return Arr::map($paymentSessionDTO->products, fn(Product $product) => [
            'identifier' => $product->identifier,
            'description' => $product->description,
            'vatPercentage' => $product->vatPercentage,
            'quantity' => $product->quantity,
            'price' => $product->price,
        ]);
    }
}
