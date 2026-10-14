<?php

declare(strict_types=1);

namespace Buckaroo\Laravel\Tests\Support;

use Buckaroo\Transaction\Request\HttpClient\HttpClientFactory;
use Buckaroo\Transaction\Request\HttpClient\HttpClientInterface;
use GuzzleHttp\Psr7\Response;
use LogicException;
use Mockery;

/**
 * Minimal, deterministic, order-based Buckaroo Client mock
 * You queue BuckarooMockRequest items, the SDK Client consumes them in order
 * Mocks the HttpClientFactory used internally by Buckaroo\Transaction\Client
 */
final class MockBuckaroo
{
    /** @var BuckarooMockRequest[] */
    private array $queue = [];

    private int $index = 0;

    public function mockTransportRequests(array $requests): void
    {
        foreach ($requests as $r) {
            if (!$r instanceof BuckarooMockRequest) {
                throw new LogicException('mockTransportRequests expects BuckarooMockRequest instances');
            }
        }
        $this->queue = array_values($requests);
        $this->index = 0;
    }

    public function assertAllConsumed(): void
    {
        $left = count($this->queue) - $this->index;
        if ($left > 0) {
            throw new LogicException("{$left} Buckaroo request(s) were not called");
        }
    }

    /** internal dispatch called by the fake client */
    public function dispatch(string $method, string $url, array $headers, ?string $raw): array
    {
        if ($this->index >= count($this->queue)) {
            throw new LogicException("Unexpected Buckaroo call with no mocks left: {$method} {$url}");
        }

        $req = $this->queue[$this->index];

        if (!$req->matches($method, $url, $headers, $raw)) {
            throw new LogicException($req->mismatchMessage($method, $url, $headers, $raw));
        }

        $this->index++;

        [$status, $respHeaders, $body, $isJson] = $req->responseSpec();
        if ($isJson) {
            $respHeaders = ['Content-Type' => 'application/json'] + $respHeaders;
            $encoded = json_encode($body, JSON_UNESCAPED_SLASHES);
            $response = new Response($status, $respHeaders, $encoded);

            return [$response, $body];
        }

        $response = new Response($status, $respHeaders, (string) $body);

        return [$response, (string) $body];
    }

    public function install(): void
    {
        /** Create a fresh overload mock for HttpClientFactory used by Client */
        $factoryOverload = Mockery::mock('overload:' . HttpClientFactory::class);

        /** Return a fake HttpClientInterface that dispatches to our queue-based mock */
        $factoryOverload
            ->shouldReceive('createClient')
            ->zeroOrMoreTimes()
            ->andReturnUsing(static function ($config) {
                return new class() implements HttpClientInterface
                {
                    public function call(string $url, array $headers, string $method, ?string $data = null)
                    {
                        /** @var MockBuckaroo|null $mock */
                        $mock = app()->bound(MockBuckaroo::class)
                            ? app(MockBuckaroo::class)
                            : null;

                        if (!$mock) {
                            throw new LogicException(
                                'No MockBuckaroo bound in the container for this test. ' .
                                'Bind it in Pest beforeEach via app()->instance(MockBuckaroo::class, $mock)'
                            );
                        }

                        return $mock->dispatch($method, $url, $headers, $data);
                    }
                };
            });
    }
}
