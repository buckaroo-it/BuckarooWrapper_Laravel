<?php
/*
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * It is available through the world-wide-web at this URL:
 * https://tldrlegal.com/license/mit-license
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@buckaroo.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@buckaroo.nl for more information.
 *
 * @copyright Copyright (c) Buckaroo B.V.
 * @license   https://tldrlegal.com/license/mit-license
 */

namespace Buckaroo\Tests\Payments;

use Buckaroo\Tests\BuckarooTestCase;
use Buckaroo\Resources\Constants\Gender;

class TransferTest extends BuckarooTestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_transfer_payment()
    {
        $response = $this->buckaroo->method('transfer')->pay([
            'invoice' => uniqid(),
            'amountDebit' => 10.10,
            'email' => 'your@email.com',
            'country' => 'NL',
            'dateDue' => date("Y-m-d"),
            'sendMail' => true,
            'customer' => [
                'gender' => Gender::MALE,
                'firstName' => 'John',
                'lastName' => 'Smith'
            ]
        ]);

        $this->assertTrue($response->isAwaitingConsumer());
    }

    /**
     * @test
     */
    public function it_creates_a_transfer_refund()
    {
        $response = $this->buckaroo->method('transfer')->refund([
            'amountCredit' => 10,
            'invoice'       => 'testinvoice 123',
            'originalTransactionKey' => '2D04704995B74D679AACC59F87XXXXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

}