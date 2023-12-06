<?php

if (! function_exists('buckaroo')) {
    /**
     * @return \Buckaroo\Laravel\Wrappers\BuckarooWrapper
     */
    function buckaroo()
    {
        return app('buckaroo.client');
    }
}
