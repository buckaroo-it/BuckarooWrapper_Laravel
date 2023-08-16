<?php

namespace App\Buckaroo\Wrappers;

use Buckaroo\BuckarooClient;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Illuminate\Contracts\Container\Container;

use Session;
class BuckarooWrapper
{
    protected Container $app;
    protected BuckarooClient $client;
    public function __construct(Container $app)
    {
        $this->app = $app;

        $credentials = Session::get('buckarooCredentials');

        $this->client = new BuckarooClient($credentials['website_key'] ?? 'xxx', $credentials['secret_key'] ?? 'xxx');
    }

    public function method(string $paymentMethod)
    {
        return $this->client->method($paymentMethod);
    }

    public function verify(array|string $data, string $header, string $url): ReplyHandler
    {
        $reply_handler = new ReplyHandler($this->client->client()->config(), $data,  $header, $url);
        $reply_handler->validate();

        return $reply_handler;
    }

    public function __get($property)
    {
        if (method_exists($this, $property)) {
            return call_user_func([$this, $property]);
        }

        $message = '%s has no property or method "%s".';

        throw new \Error(
            sprintf($message, static::class, $property)
        );
    }
}
