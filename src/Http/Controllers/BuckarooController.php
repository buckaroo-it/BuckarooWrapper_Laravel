<?php

namespace Buckaroo\Laravel\Http\Controller;

use Buckaroo\Laravel\Payments\Webhook\PayloadRequest;
use Buckaroo\Laravel\Models\BuckarooTransaction;

class BuckarooController extends Controller
{
    public function handle(PayloadRequest $request)
    {
        BuckarooTransaction::create($request->all());
        return response('ok!', 200);
    }
}
