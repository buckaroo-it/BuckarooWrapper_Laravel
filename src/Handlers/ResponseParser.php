<?php

namespace Buckaroo\Laravel\Handlers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ResponseParser extends Collection
{
    protected Request $request;

    public function __construct(array $items = [])
    {
        parent::__construct($items);
        $this->request = app(Request::class);
    }

    public function getData(): array
    {
        return $this->items;
    }

    public function handle(): self
    {
        return $this->request->header('content-type') === 'application/json' ?
            JsonParser::make($this->items) :
            FormDataParser::make($this->items);
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
