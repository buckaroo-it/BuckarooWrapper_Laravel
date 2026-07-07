<?php

/*
 *
 *  * NOTICE OF LICENSE
 *  *
 *  * This source file is subject to the MIT License
 *  * It is available through the world-wide-web at this URL:
 *  * https://tldrlegal.com/license/mit-license
 *  * If you are unable to obtain it through the world-wide-web, please send an email
 *  * to support@buckaroo.nl so we can send you a copy immediately.
 *  *
 *  * DISCLAIMER
 *  *
 *  * Do not edit or add to this file if you wish to upgrade this module to newer
 *  * versions in the future. If you wish to customize this module for your
 *  * needs please contact support@buckaroo.nl for more information.
 *  *
 *  * @copyright Copyright (c) Buckaroo B.V.
 *  * @license   https://tldrlegal.com/license/mit-license
 *
 */

namespace Buckaroo\Laravel\Tests;

use Buckaroo\BuckarooClient;

class ConfirmingCredentialsTest extends TestCase
{
    public function test_it_confirms_given_credentials()
    {
        $websiteKey = $_ENV['BPE_WEBSITE_KEY'] ?? null;
        $secretKey = $_ENV['BPE_SECRET_KEY'] ?? null;

        if (empty($websiteKey) || empty($secretKey)) {
            $this->markTestSkipped('Buckaroo credentials are not configured.');
        }

        $buckaroo = new BuckarooClient($websiteKey, $secretKey);

        $this->assertTrue($buckaroo->confirmCredential());
    }
}
