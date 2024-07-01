<?php

namespace Buckaroo\Laravel\Contracts;

use Buckaroo\Laravel\DTO\RefundSession;

interface RefundSessionModel extends SessionModel
{
    public function toDto(): RefundSession;
}
