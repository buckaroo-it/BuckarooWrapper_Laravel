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

namespace Buckaroo\Laravel\Tests;

use Buckaroo\Laravel\Buckaroo;

class ApplepayTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_applepay_redirect_payment()
    {
        $buckaroo = new Buckaroo();

        $response =  $buckaroo->payment('applepay','payRedirect',[
            'amountDebit' => 10,
            'invoice' => uniqid(),
            'servicesSelectableByClient' => 'applepay',
            'continueOnIncomplete' => '1',
        ]);

        $this->assertTrue($response->isWaitingOnUserInput());
    }

    /**
     * @test
     */
    public function it_creates_a_applepay_refund()
    {
        $buckaroo = new Buckaroo();

        $response =  $buckaroo->payment('applepay','refund',[
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }
}