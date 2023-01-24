<?php

namespace Buckaroo\BuckarooWrapper;

use Buckaroo\BuckarooClient;

class BaseService
{
    public  function client()
    {
        try {

            return new BuckarooClient("Zr7y14Zosh", "6nvt3sqvVmw438dG", "TEST");
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
