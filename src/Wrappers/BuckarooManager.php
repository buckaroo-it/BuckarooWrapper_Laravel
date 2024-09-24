<?php

namespace Buckaroo\Laravel\Wrappers;

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

    public function api()
    {
        return $this->app['buckaroo.api'];
    }

    public function getTransactionModelClass(): string
    {
        return $this->app['config']['buckaroo.transaction_model'];
    }
}
