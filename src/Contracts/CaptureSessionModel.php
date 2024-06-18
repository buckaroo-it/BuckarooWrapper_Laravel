<?php

namespace Buckaroo\Laravel\Contracts;

use Buckaroo\Laravel\DTO\CaptureSession as CaptureSessionDTO;
use Buckaroo\Laravel\Models\BuckarooTransaction;

interface CaptureSessionModel extends SessionModel
{
    public function getAuthorizedBuckarooTransaction(): BuckarooTransaction;

    public function toDto(): CaptureSessionDTO;
}
