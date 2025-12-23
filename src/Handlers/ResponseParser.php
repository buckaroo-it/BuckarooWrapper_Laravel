<?php

namespace Buckaroo\Laravel\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Str;

abstract class ResponseParser extends Collection implements ResponseParserInterface
{
    protected Request $request;
    protected array $originalItems = [];

    public function __construct(array $items = [])
    {
        $this->originalItems = $items;
        parent::__construct($items);
        $this->request = app(Request::class);
    }

    public function getOriginalItems(): array
    {
        return $this->originalItems;
    }

    public static function make($items = [])
    {
        return collect($items)->filter(fn ($item, $key) => Str::startsWith(strtolower($key), 'brq_'))->isEmpty() ?
            new JsonParser($items) :
            new FormDataParser($items);
    }

    protected function getCaseInsensitive($key, $default = null)
    {
        $foundKey = $this->keys()->first(fn($k) => strtolower($k) === strtolower($key));
        return $foundKey ? $this->get($foundKey, $default) : $default;
    }

    protected function formatAmount($amount): ?float
    {
        return is_numeric($amount) ? (float) $amount : null;
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
