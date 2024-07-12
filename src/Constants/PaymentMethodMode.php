<?php

namespace Buckaroo\Laravel\Constants;

class PaymentMethodMode
{
    const TEST = 'test';

    const LIVE = 'live';

    const DISABLED = 'disabled';

    public static function cases(): array
    {
        return [
            self::TEST,
            self::LIVE,
            self::DISABLED,
        ];
    }
}
