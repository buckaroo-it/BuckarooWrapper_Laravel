<?php

use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Laravel\Tests\TestCase;
use Buckaroo\Transaction\Response\TransactionResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;

const TEST_WEBSITE_KEY = 'test-website-key';
const TEST_SECRET_KEY = 'test-secret-key';

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(fn () => config([
        'buckaroo.website_key' => TEST_WEBSITE_KEY,
        'buckaroo.secret_key' => TEST_SECRET_KEY,
        'buckaroo.mode' => 'test',
    ]))
    ->in(__DIR__);

dataset('callback routes', [
    'push' => ['/buckaroo/push'],
    'return' => ['/buckaroo/return'],
]);

/**
 * Restores the config the package ships with, as in an app without `BPE_*` env vars.
 */
function useDefaultPackageConfig(): void
{
    config(['buckaroo' => require dirname(__DIR__) . '/config/buckaroo.php']);
    app()->forgetInstance('buckaroo.api');
}

/**
 * Signs form fields the way Buckaroo's form signature spec does: signed
 * `brq_`/`add_`/`cust_` fields sorted case-insensitively, joined as
 * `key=value` with no separator, followed by the secret, hashed with SHA-1.
 */
function signForm(array $fields, string $secret = TEST_SECRET_KEY): array
{
    $signed = array_filter(
        $fields,
        fn ($key) => in_array(explode('_', strtolower($key))[0], ['brq', 'add', 'cust'], true),
        ARRAY_FILTER_USE_KEY
    );

    uksort($signed, fn ($a, $b) => strcmp(strtolower($a), strtolower($b)));

    $data = '';
    foreach ($signed as $key => $value) {
        $data .= $key . '=' . html_entity_decode($value);
    }

    return $fields + ['brq_signature' => sha1($data . $secret)];
}

function createTransaction(array $attributes = []): BuckarooTransaction
{
    return BuckarooTransaction::create(array_merge([
        'payment_method' => 'ideal',
        'transaction_key' => 'TX1',
        'status_code' => '791',
        'is_test' => true,
        'currency' => 'EUR',
        'amount' => 10,
        'status' => 'pending',
        'service_action' => 'pay',
    ], $attributes));
}

/**
 * Replaces the Buckaroo API with a double that records every call
 * and answers each one with the given transaction response data.
 */
function fakeBuckarooApi(array $response): object
{
    $fake = new class($response)
    {
        public array $calls = [];
        private ?string $method = null;

        public function __construct(private array $response) {}

        public function method(?string $method = null): self
        {
            $this->method = $method;

            return $this;
        }

        public function __call(string $action, array $arguments): TransactionResponse
        {
            $this->calls[] = ['method' => $this->method, 'action' => $action, 'payload' => $arguments[0] ?? null];

            return new TransactionResponse(null, $this->response);
        }
    };

    app()->instance('buckaroo.api', $fake);

    return $fake;
}

/**
 * Form fields of a successful iDEAL push for transaction `TX1`.
 */
function pushFields(array $fields = []): array
{
    return array_merge([
        'brq_transactions' => 'TX1',
        'brq_statuscode' => '190',
        'brq_statusmessage' => 'Transaction successfully processed',
        'brq_amount' => '10.00',
        'brq_currency' => 'EUR',
        'brq_transaction_method' => 'ideal',
        'brq_test' => 'true',
    ], $fields);
}
