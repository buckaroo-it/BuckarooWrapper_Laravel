<?php

namespace Buckaroo\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class Buckaroo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'buckaroo';
    }
}
