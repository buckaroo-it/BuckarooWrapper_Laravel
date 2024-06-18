<?php

namespace Buckaroo\Laravel\Contracts;

interface Capturable
{
    public function getCapturePayload(): array;
}
