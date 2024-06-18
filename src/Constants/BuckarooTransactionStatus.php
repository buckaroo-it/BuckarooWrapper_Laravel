<?php

namespace Buckaroo\Laravel\Constants;

use Buckaroo\Resources\Constants\ResponseStatus;

class BuckarooTransactionStatus
{
    const STATUS_OPEN = 'open';

    const STATUS_PENDING = 'pending';

    const STATUS_PAID = 'paid';

    const STATUS_FAILED = 'failed';

    const STATUS_CANCELLED = 'cancelled';

    public static function fromTransactionStatus(string $status): string
    {
        return match ($status) {
            ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS => self::STATUS_PAID,
            ResponseStatus::BUCKAROO_AUTHORIZE_TYPE_ACCEPT,
            ResponseStatus::BUCKAROO_AUTHORIZE_TYPE_GROUP_TRANSACTION => self::STATUS_OPEN,
            ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_USER_INPUT,
            ResponseStatus::BUCKAROO_STATUSCODE_WAITING_ON_CONSUMER,
            ResponseStatus::BUCKAROO_STATUSCODE_PENDING_PROCESSING,
            ResponseStatus::BUCKAROO_STATUSCODE_PAYMENT_ON_HOLD,
            ResponseStatus::BUCKAROO_STATUSCODE_PENDING_APPROVAL => self::STATUS_PENDING,
            ResponseStatus::BUCKAROO_AUTHORIZE_TYPE_CANCEL,
            ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_USER,
            ResponseStatus::BUCKAROO_STATUSCODE_CANCELLED_BY_MERCHANT => self::STATUS_CANCELLED,
            default => self::STATUS_FAILED,
        };
    }

    public function cases(): array
    {
        return [
            self::STATUS_OPEN,
            self::STATUS_PENDING,
            self::STATUS_PAID,
            self::STATUS_FAILED,
            self::STATUS_CANCELLED,
        ];
    }
}
