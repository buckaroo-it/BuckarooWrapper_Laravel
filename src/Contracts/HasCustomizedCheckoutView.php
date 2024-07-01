<?php

namespace Buckaroo\Laravel\Contracts;

use Illuminate\Http\Request;

interface HasCustomizedCheckoutView
{
    public function checkoutView(array &$payload): bool|array;

    public function checkoutSubmit(Request $request): void;
}
