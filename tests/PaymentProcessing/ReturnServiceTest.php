<?php

namespace Buckaroo\Laravel\Tests\PaymentProcessing;

use Buckaroo\Laravel\Constants\BuckarooTransactionStatus;
use Buckaroo\Laravel\Events\PayTransactionCompleted;
use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\PaymentProcessing\ReturnService;
use Illuminate\Support\Facades\Event;

class ReturnServiceTest extends PaymentProcessingTestCase
{
    public function test_replayed_pending_return_dispatches_payment_event_once(): void
    {
        Event::fake();
        $this->createPayment(['status_code' => '791', 'status' => BuckarooTransactionStatus::STATUS_PENDING]);
        $payload = $this->signedPayload($this->payPayload([
            'brq_statuscode' => '791',
            'brq_statuscode_detail' => 'P190',
        ]));

        $this->post(route('buckaroo.return'), $payload)->assertOk();
        $this->post(route('buckaroo.return'), $payload)->assertOk();

        Event::assertDispatchedTimes(PayTransactionCompleted::class, 1);
    }

    public function test_force_process_overrides_return_replay_protection(): void
    {
        Event::fake();
        $this->createPayment(['status_code' => '791', 'status' => BuckarooTransactionStatus::STATUS_PENDING]);
        $payload = $this->payPayload([
            'brq_statuscode' => '791',
            'brq_statuscode_detail' => 'P190',
        ]);

        ReturnService::make(ReplyHandlerRequest::create('/buckaroo/return', 'POST', $payload))
            ->forceProcess()
            ->handleReturnRequest();
        ReturnService::make(ReplyHandlerRequest::create('/buckaroo/return', 'POST', $payload))
            ->forceProcess()
            ->handleReturnRequest();

        Event::assertDispatchedTimes(PayTransactionCompleted::class, 2);
    }
}
