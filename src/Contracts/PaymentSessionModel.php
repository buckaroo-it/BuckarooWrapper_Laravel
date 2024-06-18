<?php

namespace Buckaroo\Laravel\Contracts;

use Buckaroo\Laravel\DTO\PaymentSession;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface PaymentSessionModel extends SessionModel
{
    public function captures(): ?HasMany;

    public function toDto(): PaymentSession;

    public function setConfig(string $key, mixed $value): void;
}
