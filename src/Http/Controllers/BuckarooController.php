<?php

namespace Buckaroo\Laravel\Http\Controller;

use Buckaroo\Laravel\Payments\Webhook\PayloadRequest;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Illuminate\Routing\Controller as BaseController;

class BuckarooController extends BaseController
{
    public function handlePush(PayloadRequest $request)
    {
        BuckarooTransaction::create($request->all());

        return array('status' => true);
    }
}
