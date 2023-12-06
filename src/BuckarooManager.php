<?php

namespace Buckaroo\Laravel;

use Illuminate\Contracts\Container\Container;

class BuckarooManager
{
    /**
     * @var Container
     */
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @return mixed
     */
    public function client()
    {
        return $this->app['buckaroo.api'];
    }
}
