<?php

namespace Buckaroo\Laravel\Exceptions;

use Exception;

class BuckarooClientException extends Exception
{
    public static function notPayable(): self
    {
        return new static('This payment method is not payable.');
    }

    public static function notCapturable(): self
    {
        return new static('This payment method is not capturable.');
    }

    public static function invalidReplyHandler(): self
    {
        return new static('Reply handler is invalid.');
    }
}
