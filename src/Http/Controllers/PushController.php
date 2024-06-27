<?php

namespace Buckaroo\Laravel\Http\Controllers;

use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\PaymentProcessing\PushService;

class PushController
{
    public function __invoke(ReplyHandlerRequest $request, PushService $pushService)
    {
        return $pushService->handlePushRequest($request);
    }
}
