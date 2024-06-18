<?php

namespace Buckaroo\Laravel\Handlers;

use Arr;
use Buckaroo\Laravel\Contracts\ResponseParserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Str;

abstract class ResponseParser extends Collection implements ResponseParserInterface
{
    protected Request $request;

    public function __construct(array $items = [])
    {
        parent::__construct($items);
        $this->request = app(Request::class);
    }

    public static function make($items = [])
    {
        return collect($items)->filter(fn($item, $key) => Str::startsWith(strtolower($key), 'brq_'))->isEmpty() ?
            new JsonParser($items) :
            new FormDataParser($items);
    }

    public function existingPaymentMethod(?string $defaultMethod = null): ?string
    {
        $method = $this->getPaymentMethod();
        $paymentMethods = config('buckaroo.payment_methods');

        $findValidPaymentMethod = function (?string $method = null) use ($paymentMethods): ?string {
            $method = strtolower($method);

            foreach ($paymentMethods as $key => $config) {
                if ($method === ($value = is_string($config) ? $config : $key)) {
                    return $value;
                }

                if (is_array($config)) {
                    if (in_array($method, Arr::get($config, 'aliases', []))) {
                        return $key;
                    }

                    if (in_array($method, Arr::get($config, 'children', []))) {
                        return $method;
                    }
                }
            }

            return null;
        };

        return $findValidPaymentMethod($method) ?? $findValidPaymentMethod($defaultMethod);
    }

    protected function formatAmount($amount): ?float
    {
        return is_numeric($amount) ? (float)$amount : null;
    }

    protected function getDeep($key = null, $default = null)
    {
        return data_get(
            $this->all(),
            $key,
            $default
        );
    }
}
