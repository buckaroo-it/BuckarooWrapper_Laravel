<?php

namespace Buckaroo\Laravel\Tests;

use Buckaroo\Resources\Constants\RecipientCategory;

use Buckaroo\Laravel\Buckaroo;

class AfterPayTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function it_creates_a_afterpay_payment()
    {
        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('afterpay', 'pay', $this->getPaymentPayload());

        $this->assertTrue($response->isRejected());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_authorize()
    {
        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('afterpay', 'authorize', $this->getPaymentPayload());

        $this->assertTrue($response->isRejected());
    }
    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_capture()
    {
        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('afterpay', 'capture', $this->getPaymentPayload([
            'originalTransactionKey'    => 'D5127080BA1D4644856FECDC560FXXXX'
        ]));

        $this->assertTrue($response->isValidationFailure());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpay_refund()
    {
        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('afterpay', 'refund',[
            'invoice'   => 'testinvoice 123', //Set invoice number of the transaction to refund
            'originalTransactionKey' => '4E8BD922192746C3918BF4077CXXXXXX', //Set transaction key of the transaction to refund
            'amountCredit' => 1.23
        ]);

        $this->assertTrue($response->isValidationFailure());
    }

    private function getPaymentPayload(?array $additional = null): array
    {
        $payload = [
            'amountDebit' => 50.30,
            'order' => uniqid(),
            'invoice' => uniqid(),
            'billing' => [
                'recipient' => [
                    'category' => RecipientCategory::PERSON,
                    'careOf' => 'John Smith',
                    'title' => 'Mrs',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                    'birthDate' => '1990-01-01',
                    'conversationLanguage' => 'NL',
                    'identificationNumber' => 'IdNumber12345',
                    'customerNumber' => 'customerNumber12345'
                ],
                'address' => [
                    'street' => 'Hoofdstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'a',
                    'zipcode' => '1234AB',
                    'city' => 'Heerenveen',
                    'country' => 'NL'
                ],
                'phone' => [
                    'mobile' => '0698765433',
                    'landline' => '0109876543'
                ],
                'email' => 'test@buckaroo.nl'
            ],
            'shipping' => [
                'recipient' => [
                    'category' => RecipientCategory::COMPANY,
                    'careOf' => 'John Smith',
                    'companyName' => 'Buckaroo B.V.',
                    'firstName' => 'John',
                    'lastName' => 'Do',
                    'chamberOfCommerce' => '12345678'
                ],
                'address' => [
                    'street' => 'Kalverstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode' => '4321EB',
                    'city' => 'Amsterdam',
                    'country' => 'NL'
                ],
            ],
            'articles' => [
                [
                    'identifier' => 'Articlenumber1',
                    'description' => 'Blue Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '2',
                    'price' => '20.10'
                ],
                [
                    'identifier' => 'Articlenumber2',
                    'description' => 'Red Toy Car',
                    'vatPercentage' => '21',
                    'quantity' => '1',
                    'price' => '10.10'
                ],
            ]
        ];

        if ($additional) {
            return array_merge($additional, $payload);
        }

        return $payload;
    }
}
