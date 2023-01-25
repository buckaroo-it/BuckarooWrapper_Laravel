<?php

namespace Buckaroo\Laravel\Tests;

use Buckaroo\Laravel\Buckaroo;

class AlipayTest extends TestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_alipay_payment()
    {

        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('alipay', 'pay', [
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'useMobileView' => true
        ]);


        $this->assertTrue($response->isPendingProcessing());
    }

    /**
     * @test
     */
    public function it_creates_a_alipay_refund()
    {
        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('alipay', 'refund', [
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX',
        ]);

        $this->assertTrue($response->isFailed());
    }
}