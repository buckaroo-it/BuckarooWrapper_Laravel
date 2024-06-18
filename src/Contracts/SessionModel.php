<?php

namespace Buckaroo\Laravel\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

interface SessionModel
{
    public function paymentMethod(): ?BelongsTo;

    public function buckarooTransactions(): ?MorphMany;

    public function getTransactionDescription(): string;
}
