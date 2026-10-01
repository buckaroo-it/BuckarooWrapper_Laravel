<?php

namespace Buckaroo\Laravel\Tests\PaymentProcessing;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Events\RefundTransactionCompleted;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Illuminate\Support\Facades\Event;

class PushServiceTest extends PaymentProcessingTestCase
{
    public function test_unknown_refund_push_creates_refund_without_changing_parent_payment(): void
    {
        Event::fake();

        $parent = BuckarooTransaction::create([
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
        ]);
        $originalParent = $parent->only(['status_code', 'status_subcode', 'status', 'amount', 'related_transaction_key']);

        $payload = $this->signedPayload([
            'brq_transactions' => 'REFUND999',
            'brq_relatedtransaction_refund' => 'PAYMENT123',
            'brq_statuscode' => '190',
            'brq_statuscode_detail' => 'S001',
            'brq_statusmessage' => 'Transaction successful',
            'brq_transaction_method' => 'ideal',
            'brq_amount_credit' => '10.00',
            'brq_currency' => 'EUR',
            'brq_invoicenumber' => 'INV123',
            'brq_test' => 'true',
        ]);

        $this->post(route('buckaroo.push'), $payload)->assertOk();

        Event::assertNotDispatched(PayTransactionCompleted::class);
        Event::assertDispatchedTimes(RefundTransactionCompleted::class, 1);
        $this->assertDatabaseHas('buckaroo_transactions', [
            'transaction_key' => 'REFUND999',
            'related_transaction_key' => 'PAYMENT123',
            'service_action' => 'push/refund',
            'amount' => -10,
            'order' => 'ORDER123',
        ]);
        $this->assertSame($originalParent, $parent->fresh()->only(array_keys($originalParent)));
    }

    public function test_unknown_pending_refund_push_creates_refund_without_completed_event(): void
    {
        Event::fake();
        $this->createPayment();

        $payload = $this->signedPayload([
            'brq_transactions' => 'REFUND999',
            'brq_relatedtransaction_refund' => 'PAYMENT123',
            'brq_statuscode' => '791',
            'brq_statuscode_detail' => 'P190',
            'brq_statusmessage' => 'Pending processing',
            'brq_transaction_method' => 'ideal',
            'brq_amount_credit' => '10.00',
            'brq_currency' => 'EUR',
            'brq_invoicenumber' => 'INV123',
            'brq_test' => 'true',
        ]);

        $this->post(route('buckaroo.push'), $payload)->assertOk();

        Event::assertNotDispatched(RefundTransactionCompleted::class);
        $this->assertDatabaseHas('buckaroo_transactions', [
            'transaction_key' => 'REFUND999',
            'status' => BuckarooTransactionStatus::STATUS_PENDING,
        ]);
    }

    public function test_known_refund_push_updates_the_existing_refund(): void
    {
        Event::fake();

        $refund = BuckarooTransaction::create([
            'payment_method' => 'ideal',
            'transaction_key' => 'REFUND999',
            'related_transaction_key' => 'PAYMENT123',
            'status_code' => '791',
            'status_subcode' => null,
            'status_subcode_description' => null,
            'order' => 'ORDER123',
            'invoice' => 'INV123',
            'is_test' => true,
            'currency' => 'EUR',
            'amount' => -10,
            'status' => BuckarooTransactionStatus::STATUS_PENDING,
            'service_action' => 'refund',
        ]);

        $payload = $this->signedPayload([
            'brq_transactions' => 'REFUND999',
            'brq_relatedtransaction_refund' => 'PAYMENT123',
            'brq_statuscode' => '190',
            'brq_statuscode_detail' => 'S001',
            'brq_statusmessage' => 'Transaction successful',
            'brq_transaction_method' => 'ideal',
            'brq_amount_credit' => '10.00',
            'brq_currency' => 'EUR',
            'brq_invoicenumber' => 'INV123',
            'brq_test' => 'true',
        ]);

        $this->post(route('buckaroo.push'), $payload)->assertOk();

        $this->assertSame(1, BuckarooTransaction::count());
        $this->assertSame('190', $refund->fresh()->status_code);
        $this->assertSame(BuckarooTransactionStatus::STATUS_PAID, $refund->fresh()->status);
        Event::assertDispatchedTimes(RefundTransactionCompleted::class, 1);
    }

    public function test_replayed_success_push_dispatches_payment_event_once(): void
    {
        Event::fake();
        $this->createPayment(['status_code' => '791', 'status' => BuckarooTransactionStatus::STATUS_PENDING]);
        $payload = $this->signedPayload($this->payPayload(['brq_statuscode' => '190']));

        $this->post(route('buckaroo.push'), $payload)->assertOk();
        $this->post(route('buckaroo.push'), $payload)->assertOk();
        $this->post(route('buckaroo.push'), $payload)->assertOk();

        Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
    }

    public function test_status_change_from_pending_to_success_dispatches_payment_event(): void
    {
        Event::fake();
        $this->createPayment(['status_code' => '0', 'status' => BuckarooTransactionStatus::STATUS_FAILED]);

        $this->post(route('buckaroo.push'), $this->signedPayload($this->payPayload([
            'brq_statuscode' => '791',
            'brq_statuscode_detail' => 'P190',
        ])))->assertOk();
        $this->post(route('buckaroo.push'), $this->signedPayload($this->payPayload([
            'brq_statuscode' => '190',
            'brq_statuscode_detail' => 'S001',
        ])))->assertOk();

        Event::assertDispatchedTimes(PayTransactionCompleted::class, 2);
    }

    public function test_related_transaction_dispatches_payment_event_only_when_created(): void
    {
        Event::fake();
        $this->createPayment(['related_transaction_key' => 'GROUP123']);
        $payload = $this->signedPayload($this->payPayload([
            'brq_transactions' => 'PARTIAL456',
            'brq_relatedtransaction_partialpayment' => 'GROUP123',
            'brq_amount' => '5.00',
        ]));

        $this->post(route('buckaroo.push'), $payload)->assertOk();
        $this->post(route('buckaroo.push'), $payload)->assertOk();

        $this->assertSame(2, BuckarooTransaction::count());
        Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
    }
}
