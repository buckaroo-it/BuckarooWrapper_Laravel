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

    public static function paymentSessionNotSet(): self
    {
        return new static('Payment session is not set.');
    }

    public static function replyHandlerInvalid(): self
    {
        return new static('Reply handler is invalid.');
    }

    public static function paymentMethodNotPayable(): self
    {
        return new static('Payment method is not payable.');
    }

    public static function secretNotValid(): self
    {
        return new static('Secret is not valid.');
    }

    public static function refundSessionNotSet(): self
    {
        return new static('Refund session is not set.');
    }

    public static function voidSessionNotSet(): self
    {
        return new static('Void session is not set.');
    }

    public static function captureSessionNotSet(): self
    {
        return new static('Capture session is not set.');
    }
}
