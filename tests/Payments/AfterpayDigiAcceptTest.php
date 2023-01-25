<?php

namespace Buckaroo\Laravel\Tests;

use Buckaroo\Resources\Constants\Gender;

use Buckaroo\Laravel\Buckaroo;

class AfterpayDigiAcceptTest extends TestCase
{
    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_payment()
    {

        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('afterpaydigiaccept', 'pay', $this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_authorize()
    {

        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('afterpaydigiaccept', 'authorize', $this->getPaymentPayload());

        $this->assertTrue($response->isSuccess());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_capture()
    {

        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('afterpaydigiaccept', 'capture', $this->getPaymentPayload([
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
        ]));


        $this->assertTrue($response->isFailed());
    }

    /**
     * @return void
     * @test
     */
    public function it_creates_a_afterpaydigiaccept_refund()
    {
        $buckaroo = new Buckaroo();

        $response = $buckaroo->payment('afterpaydigiaccept', 'refund', [
            'amountCredit' => 10,
            'invoice' => '10000480',
            'originalTransactionKey' => '9AA4C81A08A84FA7B68E6A6A6291XXXX'
        ]);

        $this->assertTrue($response->isFailed());
    }

    private function getPaymentPayload(array $additionalParameters = null): array
    {
        $payload = [
            'amountDebit' => 40.50,
            'order' => uniqid(),
            'invoice' => uniqid(),
            'b2b' => true,
            'addressesDiffer' => true,
            'customerIPAddress' => '0.0.0.0',
            'shippingCosts' => 0.5,
            'costCentre' => 'Test',
            'department' => 'Test',
            'establishmentNumber' => '123456',
            'billing' => [
                'recipient' => [
                    'gender' => Gender::FEMALE,
                    'initials' => 'AB',
                    'lastName' => 'Do',
                    'birthDate' => '1990-01-01',
                    'culture' => 'NL'
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
                    'mobile' => '0698765433'
                ],
                'email' => 'test@buckaroo.nl'
            ],
            'shipping' => [
                'recipient' => [
                    'culture' => 'NL',
                    'gender' => Gender::MALE,
                    'initials' => 'YJ',
                    'lastName' => 'Jansen',
                    'companyName' => 'Buckaroo B.V.',
                    'birthDate' => '1990-01-01',
                    'chamberOfCommerce' => '12345678',
                    'vatNumber' => 'NL12345678',
                ],
                'address' => [
                    'street' => 'Kalverstraat',
                    'houseNumber' => '13',
                    'houseNumberAdditional' => 'b',
                    'zipcode' => '4321EB',
                    'city' => 'Amsterdam',
                    'country' => 'NL'
                ],
                'phone' => [
                    'mobile' => '0698765433'
                ],
                'email' => 'test@buckaroo.nl',
            ],
            'articles' => [
                [
                    'identifier' => uniqid(),
                    'description' => 'Blue Toy Car',
                    'price' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1'
                ],
                [
                    'identifier' => uniqid(),
                    'description' => 'Red Toy Car',
                    'price' => '10.00',
                    'quantity' => '2',
                    'vatCategory' => '1'
                ],
            ]
        ];

        if ($additionalParameters) {
            $payload = array_merge($payload, $additionalParameters);
        }

        return $payload;
    }
}