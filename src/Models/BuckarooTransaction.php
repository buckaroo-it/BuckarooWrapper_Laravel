<?php

namespace Buckaroo\Laravel\Models;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Handlers\BuckarooPayloadFactory;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Resources\Constants\ResponseStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Str;

class BuckarooTransaction extends Model
{
    protected $fillable = [
        'payment_method',
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

    public function scopeSuccessfulTransactions(Builder $query): void
    {
        $query->where('status_code', ResponseStatus::BUCKAROO_STATUSCODE_SUCCESS)
            ->where('status', BuckarooTransactionStatus::STATUS_PAID);
    }

    public function scopeFromAction(Builder $query, string $action): void
    {
        $query->where('service_action', 'LIKE', "%{$action}");
    }

    public function scopeCompleted(Builder $query, ?string $action = null): void
    {
        $query->successfulTransactions();

        if ($action) {
            $query->fromAction($action);
        }
    }

    public function refunds()
    {
        return $this->hasMany(static::class, 'related_transaction_key', 'transaction_key')->fromAction('refund');
    }

    public function relatedTransaction()
    {
        return $this->hasOne(static::class, 'transaction_key', 'related_transaction_key');
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

    public function getPaymentGateway()
    {
        return BuckarooPayloadFactory::getPayload($this->payment_method);
    }
}
