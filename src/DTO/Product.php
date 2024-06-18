<?php

namespace Buckaroo\Laravel\DTO;

class Product
{
    public string $identifier;
    public ?string $description;
    public ?float $vatPercentage;
    public int $quantity;
    public float $price;

    public function __construct(
        string  $identifier,
        ?string $description,
        ?float  $vatPercentage,
        int     $quantity,
        float   $price
    )
    {
        $this->identifier = $identifier;
        $this->description = $description;
        $this->vatPercentage = $vatPercentage;
        $this->quantity = $quantity;
        $this->price = $price;
    }
}
