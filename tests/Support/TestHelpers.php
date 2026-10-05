<?php

namespace Buckaroo\Laravel\Tests\Support;

use Buckaroo\Laravel\Models\BuckarooTransaction;

class TestHelpers
{
    public function mockBuckaroo(): MockBuckaroo
    {
        return app(MockBuckaroo::class);
    }

    /**
     * A signed push for a successful iDEAL payment of transaction `TX1`.
     */
    public function pushPayloadFormData(array $overrides = [], ?string $secretKey = null): array
    {
        return $this->signFormData(array_merge([
            'brq_transactions' => 'TX1',
            'brq_statuscode' => '190',
            'brq_statusmessage' => 'Transaction successfully processed',
            'brq_amount' => '10.00',
            'brq_currency' => 'EUR',
            'brq_transaction_method' => 'ideal',
            'brq_test' => 'true',
        ], $overrides), $secretKey);
    }

    public function signFormData(array $data, ?string $secretKey = null): array
    {
        return $data + ['brq_signature' => $this->generateBuckarooSignature($data, $secretKey)];
    }

    /**
     * Buckaroo's form signature: signed `brq_`/`add_`/`cust_` fields sorted case-insensitively,
     * joined as `key=value` without a separator, followed by the secret, hashed with SHA-1.
     */
    public function generateBuckarooSignature(array $data, ?string $secretKey = null): string
    {
        $secretKey = $secretKey ?? config('buckaroo.secret_key');

        $filtered = array_filter($data, function ($key) {
            $key = strtolower($key);

            return $key !== 'brq_signature' && in_array(explode('_', $key)[0], ['brq', 'add', 'cust'], true);
        }, ARRAY_FILTER_USE_KEY);

        uksort($filtered, fn ($a, $b) => strcmp(strtolower($a), strtolower($b)));

        $dataString = '';
        foreach ($filtered as $key => $value) {
            $dataString .= $key . '=' . html_entity_decode((string) $value);
        }

        return sha1($dataString . $secretKey);
    }

    public function createTransaction(array $attributes = []): BuckarooTransaction
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
     * Restores the config the package ships with, as in an app without `BPE_*` env vars.
     */
    public function useDefaultPackageConfig(): void
    {
        config(['buckaroo' => require dirname(__DIR__, 2) . '/config/buckaroo.php']);
        app()->forgetInstance('buckaroo.api');
    }
}
