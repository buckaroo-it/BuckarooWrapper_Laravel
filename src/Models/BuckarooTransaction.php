<?php

namespace Buckaroo\Laravel\Models;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\DTO\PaymentMethod as PaymentMethodDTO;
use Buckaroo\Resources\Constants\ResponseStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Str;

class BuckarooTransaction extends Model
{
    protected $fillable = [
        'payment_method_id',
        'related_transaction_key',
        'transaction_key',
        'status_code',
        'status_subcode',
        'status_subcode_description',
        'order',
        'invoice',
        'is_test',
        'currency',
        'amount',
        'status',
        'service_action',
    ];
    protected $casts = [
        'is_test' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function scopeFromResponse($query, ResponseParserInterface $responseParser): void
    {
        $transactionKey = $responseParser->getTransactionKey();
        $relatedTransactionKey = $responseParser->getRelatedTransactionPartialPayment();

        if ($transactionKey) {
            $query->orWhere('transaction_key', $transactionKey);
            $query->orderByRaw('transaction_key = ? DESC', [$transactionKey]);
        }

        if ($relatedTransactionKey) {
            $query->orWhere('related_transaction_key', $relatedTransactionKey);
        }
    }

    public function payable()
    {
        return $this->morphTo();
    }

    public function scopePaid(Builder $query): void
    {
        $query->where('status_code', ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS)
            ->where('status', BuckarooTransactionStatus::STATUS_PAID)
            ->where('service_action', 'LIKE', '%pay');
    }

    public function scopeAuthorized(Builder $query): void
    {
        $query->where('status_code', ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS)
            ->where('status', BuckarooTransactionStatus::STATUS_PAID)
            ->where('service_action', 'LIKE', '%authorize');
    }

    public function scopeRefunded(Builder $query): void
    {
        $query->where('status_code', ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS)
            ->where('status', BuckarooTransactionStatus::STATUS_PAID)
            ->where('service_action', 'LIKE', '%refund');
    }

    public function getPaymentMethodDTO()
    {
        return new PaymentMethodDTO(serviceCode: $this->payment_method_id);
    }

    public function refunds()
    {
        return $this->hasMany(static::class, 'related_transaction_key', 'transaction_key')
            ->where('service_action', 'LIKE', '%refund');
    }

    public function isPushAction(): bool
    {
        return $this->hasServiceAction('push/');
    }

    public function hasServiceAction(string $action)
    {
        return Str::contains($this->service_action, $action);
    }

    public function isReturnAction(): bool
    {
        return $this->hasServiceAction('return/');
    }

    protected function serviceAction(): Attribute
    {
        return Attribute::make(
            set: fn (mixed $value, array $attributes) => Str::of($value)
                ->explode('/')
                ->unique()
                ->join('/'),
        );
    }
}
