<?php

use Buckaroo\Laravel\PaymentMethods;

return [
    'website_key' => env('BPE_WEBSITE_KEY', 'XXX'),
    'secret_key' => env('BPE_SECRET_KEY', 'XXX'),
    'mode' => env('BPE_MODE', 'live'),
    'use_noservice' => env('BPE_USE_NOSERVICE', false),
    'transaction_model' => Buckaroo\Laravel\Models\BuckarooTransaction::class,
    'payment-methods' => [
        'ideal' => [
            'class' => PaymentMethods\Ideal\Ideal::class,
        ],
        'bancontactmrcash' => [
            'class' => PaymentMethods\Bancontact\Bancontact::class,
        ],
        'payconiq',
        'giropay',
        'kbc' => [
            'aliases' => ['KbcPaymentButton'],
        ],
        'belfius',
        'cards' => [
            'class' => PaymentMethods\CreditCard\CreditCard::class,
            'children' => [
                'mastercard',
                'visa',
                'maestro',
                'cartebleuevisa',
                'vpay',
                'cartebancaire',
                'dankort',
                'nexi',
                'postepay',
                'amex',
            ],
        ],
        'afterpay' => [
            'class' => PaymentMethods\Afterpay\Afterpay::class,
        ],
        'giftcard' => [
            'class' => PaymentMethods\GiftCard\GiftCard::class,
            'children' => [
                'boekenbon',
                'fashionucadeaukaart',
                'fashioncheque',
                'vvvgiftcard',
                'webshopgiftcard',
                'digitalebioscoopbon',
                'yourgift',
            ],
        ],
        'eps',
        'multibanco',
        'mbway',
        'przelewy24' => [
            'class' => PaymentMethods\Przelewy24\Przelewy24::class,
        ],
        'sofort' => [
            'aliases' => ['sofortueberweisung'],
        ],
        'trustly' => [
            'class' => PaymentMethods\Trustly\Trustly::class,
        ],
        'wechatpay',
        'transfer' => [
            'class' => PaymentMethods\Transfer\Transfer::class,
        ],
        'paybybank' => [
            'class' => PaymentMethods\PayByBank\PayByBank::class,
        ],
        'billink' => [
            'class' => PaymentMethods\Billink\Billink::class,
        ],
        'in3' => [
            'class' => PaymentMethods\In3\In3::class,
        ],
        'knaken',
        'blik' => [
            'class' => PaymentMethods\Blik\Blik::class,
        ],
    ],
];
