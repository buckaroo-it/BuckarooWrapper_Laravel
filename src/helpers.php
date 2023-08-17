<?php

if (! function_exists('buckaroo')) {
    /**
     * @return \Mollie\Laravel\Wrappers\BuckarooWrapper
     */
    function buckaroo()
    {
        return app('buckaroo');
    }
}
