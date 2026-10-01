<?php

namespace Buckaroo\Laravel\Tests\Wrappers;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Buckaroo\Laravel\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;

class MissingBuckarooKeysTest extends TestCase
{
    use RefreshDatabase;

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('buckaroo.website_key');
        $app['config']->set('buckaroo.secret_key');
        $app['config']->set('buckaroo.mode', 'test');
        $app['config']->set('buckaroo.routes.load', true);
        $app['config']->set('buckaroo.routes.prefix', 'buckaroo');
    }

    public function test_push_signed_with_default_placeholder_is_rejected_when_keys_are_missing(): void
    {
        $this->createPayment();

        $this->post(route('buckaroo.push'), $this->signedPayPayload('XXX'))->assertStatus(400);
    }

    public function test_client_can_be_initialized_manually_when_config_keys_are_missing(): void
    {
        $this->createPayment();
        Buckaroo::api()->setBuckarooClient('KEY', 'SECRET', 'test');

        $this->post(route('buckaroo.push'), $this->signedPayPayload('SECRET'))->assertOk();
    }

    public function test_using_unconfigured_client_throws_clear_exception(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Buckaroo keys are not configured');

        Buckaroo::api()->client();
    }

    public function test_client_stays_unconfigured_when_only_one_key_is_present(): void
    {
        $this->app['config']->set('buckaroo.website_key', 'KEY');
        $this->assertFalse(Buckaroo::api()->validateBody([]));

        $this->app->forgetInstance('buckaroo.api');
        $this->app['config']->set('buckaroo.website_key');
        $this->app['config']->set('buckaroo.secret_key', 'SECRET');
        $this->assertFalse(Buckaroo::api()->validateBody([]));
    }

    public function test_dynamic_call_on_unconfigured_client_throws_clear_exception(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Buckaroo keys are not configured');

        Buckaroo::api()->method('ideal');
    }

    private function createPayment(): void
    {
        BuckarooTransaction::create([
            'payment_method' => 'ideal',
            'transaction_key' => 'PAYMENT123',
            'related_transaction_key' => null,
            'status_code' => '791',
            'status_subcode' => 'P190',
            'status_subcode_description' => 'Pending',
            'order' => 'ORDER123',
            'invoice' => 'INV123',
            'is_test' => true,
            'currency' => 'EUR',
            'amount' => 25,
            'status' => BuckarooTransactionStatus::STATUS_PENDING,
            'service_action' => 'pay',
        ]);
    }

    private function signedPayPayload(string $secret): array
    {
        $payload = [
            'brq_transactions' => 'PAYMENT123',
            'brq_statuscode' => '190',
            'brq_statuscode_detail' => 'S001',
            'brq_statusmessage' => 'Transaction successful',
            'brq_transaction_method' => 'ideal',
            'brq_amount' => '25.00',
            'brq_currency' => 'EUR',
            'brq_invoicenumber' => 'INV123',
            'brq_test' => 'true',
        ];
        uksort($payload, static fn ($left, $right) => strcmp(strtolower($left), strtolower($right)));
        $signedString = implode('', array_map(
            static fn ($value, $key) => $key . '=' . html_entity_decode((string) $value),
            $payload,
            array_keys($payload)
        ));
        $payload['brq_signature'] = sha1($signedString . $secret);

        return $payload;
    }
}
