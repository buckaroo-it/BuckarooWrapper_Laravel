<?php

namespace App\Buckaroo\Handlers\Payload;

use Buckaroo\PaymentMethods\Afterpay\Afterpay;
use Buckaroo\PaymentMethods\iDeal\iDeal;

class BuckarooPayloadFactory
{

    public static function getPayload(object $paymentName)
    {
        switch (get_class($paymentName)) {
            case iDeal::class:
                return new iDealPayload;
//            case Billink::class:
//                return new BillinkPayload($paymentFacade, $payment, $paymentConfig, $cart);
//            case Bancontact::class:
//                return new BancontactPayload($paymentFacade, $payment, $paymentConfig, $cart);
//            case Creditcard::class:
//                return new CreditcardPayload($paymentFacade, $payment, $paymentConfig, $cart);
//            case Giftcard::class:
//                return new GiftcardPayload($paymentFacade, $payment, $paymentConfig, $cart);
//            case BankTransfer::class:
//                return new TransferPayload($paymentFacade, $payment, $paymentConfig, $cart);
//            case ApplePay::class:
//                return new ApplePayPayload($paymentFacade, $payment, $paymentConfig, $cart);
//            case KlarnaKP::class:
//                return new KlarnaPayload($paymentFacade, $payment, $paymentConfig, $cart);
            case Afterpay::class:
                return new RivertyPayload;
//            case Trustly::class:
//                return new TrustlyPayload($paymentFacade, $payment, $paymentConfig, $cart);
            default:
                return new DefaultPayload;
        }
    }
}
