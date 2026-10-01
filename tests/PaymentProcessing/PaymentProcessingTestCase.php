<?php

namespace Buckaroo\Laravel\Tests\PaymentProcessing;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Laravel\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class PaymentProcessingTestCase extends TestCase
{
    use RefreshDatabase;

    protected const WEBSITE_KEY = 'test-website-key';

    protected const SECRET_KEY = 'test-secret-key';

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('buckaroo.website_key', static::WEBSITE_KEY);
        $app['config']->set('buckaroo.secret_key', static::SECRET_KEY);
        $app['config']->set('buckaroo.mode', 'test');
        $app['config']->set('buckaroo.routes.load', true);
        $app['config']->set('buckaroo.routes.prefix', 'buckaroo');
    }

    protected function createPayment(array $overrides = []): BuckarooTransaction
    {
        return BuckarooTransaction::create(array_merge([
            'payment_method' => 'ideal',
            'transaction_key' => 'PAYMENT123',
            'related_transaction_key' => null,
            'status_code' => '190',
            'status_subcode' => 'S001',
            'status_subcode_description' => 'Transaction successful',
            'order' => 'ORDER123',
            'invoice' => 'INV123',
            'is_test' => true,
            'currency' => 'EUR',
            'amount' => 25,
            'status' => BuckarooTransactionStatus::STATUS_PAID,
            'service_action' => 'pay',
        ], $overrides));
    }

    protected function payPayload(array $overrides = []): array
    {
        return array_merge([
            'brq_transactions' => 'PAYMENT123',
            'brq_statuscode' => '190',
            'brq_statuscode_detail' => 'S001',
            'brq_statusmessage' => 'Transaction successful',
            'brq_transaction_method' => 'ideal',
            'brq_amount' => '25.00',
            'brq_currency' => 'EUR',
            'brq_invoicenumber' => 'INV123',
            'brq_test' => 'true',
        ], $overrides);
    }

    protected function signedPayload(array $payload): array
    {
        $signedFields = $payload;
        uksort($signedFields, static fn ($left, $right) => strcmp(strtolower($left), strtolower($right)));

        $signedString = implode('', array_map(
            static fn ($value, $key) => $key . '=' . html_entity_decode((string) $value),
            $signedFields,
            array_keys($signedFields)
        ));
        $payload['brq_signature'] = sha1($signedString . static::SECRET_KEY);

        return $payload;
    }
}
