<?php

namespace Buckaroo\Laravel\DTO;

class Product extends BaseData
{
    public string $identifier;
    public ?string $description = '';
    public ?float $vatPercentage = null;
    public int $quantity;
    public float $price;

    public function __construct(
        string $identifier,
        int $quantity,
        float $price,
        ?string $description = '',
        ?float $vatPercentage = null,
    ) {
        $this->identifier = $identifier;
        $this->description = $description;
        $this->vatPercentage = $vatPercentage;
        $this->quantity = $quantity;
        $this->price = $price;
    }
}
