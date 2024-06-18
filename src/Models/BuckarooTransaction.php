<?php

namespace Buckaroo\Laravel\Models;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Buckaroo\Laravel\Contracts\SessionModel;
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
        'test',
        'currency',
        'amount',
        'status',
        'service_action',
    ];
    protected $casts = [
        'test' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public static function storeFromTransactionResponse(ResponseParserInterface $transactionResponse, SessionModel $payable, array $additionalData = [])
    {
        $paymentSessionDto = $payable->toDto();

        return $payable->buckarooTransactions()->create([
            'payment_method_id' => $additionalData['payment_method_id'] ?? $transactionResponse->existingPaymentMethod($paymentSessionDto->paymentMethod),
            'related_transaction_key' => $additionalData['related_transaction_key'] ?? $transactionResponse->getRelatedTransactionPartialPayment(),
            'transaction_key' => $transactionResponse->getTransactionKey(),
            'status_code' => $transactionResponse->getStatusCode(),
            'status_subcode' => $transactionResponse->getSubStatusCode(),
            'status_subcode_description' => $transactionResponse->getSubCodeMessage(),
            'order' => $additionalData['order'] ?? $transactionResponse->get('Order'),
            'invoice' => $transactionResponse->getInvoice(),
            'test' => $transactionResponse->isTest(),
            'currency' => $transactionResponse->getCurrency(),
            'amount' => $additionalData['amount'] ?? $transactionResponse->getAmount() ?? $transactionResponse->getAmountDebit(),
            'status' => BuckarooTransactionStatus::fromTransactionStatus($transactionResponse->getStatusCode()),
            'service_action' => $additionalData['action'],
        ]);
    }

    public static function findFromResponse(ResponseParserInterface $responseParser): ?static
    {
        $transaction = static::query()
            ->where('transaction_key', $responseParser->getTransactionKey())
            ->latest()
            ->first();

        // orWhere in this case doesn't worked in above query.
        // because it's bypassing transaction_key condition
        if (!$transaction && ($relatedTransactionKey = $responseParser->getRelatedTransactionPartialPayment())) {
            $transaction = static::query()
                ->where('related_transaction_key', $relatedTransactionKey)
                ->latest()
                ->first();
        }

        return $transaction;
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
            set: fn(mixed $value, array $attributes) => Str::of($value)
                ->explode('/')
                ->unique()
                ->join('/'),
        );
    }
}
