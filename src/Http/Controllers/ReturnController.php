<?php

namespace Buckaroo\Laravel\Http\Controllers;

use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\PaymentProcessing\ReturnService;

class ReturnController
{
    public function __invoke(ReplyHandlerRequest $request, ReturnService $returnService)
    {
        return redirect($returnService->handleReturnRequest($request));
    }
}
