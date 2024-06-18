<?php

namespace Buckaroo\Laravel\Contracts;

use Buckaroo\Laravel\DTO\VoidSession as VoidSessionDTO;
use Buckaroo\Laravel\Models\BuckarooTransaction;

interface VoidSessionModel extends SessionModel
{
    public function getAuthorizedBuckarooTransaction(): BuckarooTransaction;

    public function toDto(): VoidSessionDTO;
}
