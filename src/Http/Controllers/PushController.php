<?php

namespace Buckaroo\Laravel\Http\Controllers;

use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\PaymentProcessing\PushService;

class PushController
{
    public function __invoke()
    {
        return PushService::make(app(ReplyHandlerRequest::class))->handlePushRequest();
    }
}
