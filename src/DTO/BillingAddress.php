<?php

namespace Buckaroo\Laravel\DTO;

class BillingAddress
{
    public string $city;
    public string $line1;
    public ?string $line2;
    public ?string $company;
    public string $lastName;
    public string $firstName;
    public string $postalCode;
    public string $countryCode;
    public ?string $locale;

    public function __construct(
        string  $city,
        string  $line1,
        ?string $line2,
        ?string $company,
        string  $firstName,
        string  $lastName,
        string  $postalCode,
        string  $countryCode,
        ?string $locale
    )
    {
        $this->city = $city;
        $this->line1 = $line1;
        $this->line2 = $line2;
        $this->company = $company;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->postalCode = $postalCode;
        $this->countryCode = $countryCode;
        $this->locale = $locale;
    }
}
