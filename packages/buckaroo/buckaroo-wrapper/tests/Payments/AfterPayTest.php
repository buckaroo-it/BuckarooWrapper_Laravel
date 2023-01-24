<?php


namespace Payments;

use Tests\TestCase;
use Buckaroo\BuckarooWrapper\Buckaroo;
use Buckaroo\Resources\Constants\RecipientCategory;

class AfterPayTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $payementType = 'afterpay';

        $method = 'pay';

        $data = [
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

        $buckaroo = new Buckaroo();
        $response = $buckaroo->payment($payementType, $method, $data);
        $this->assertTrue(true);
    }
}
