<?php

namespace Buckaroo\BuckarooWrapper;

use Buckaroo\BuckarooWrapper\Validate\AfterpayDigiAccept;
use Buckaroo\BuckarooWrapper\Validate\Afterpay;
use Buckaroo\BuckarooWrapper\Validate\Alipay;
use Buckaroo\BuckarooWrapper\Validate\ApplePay;
use Buckaroo\BuckarooWrapper\Validate\Bancontact;
use Buckaroo\BuckarooWrapper\Validate\BankTransfer;
use Buckaroo\BuckarooWrapper\Validate\Belfius;
use Buckaroo\BuckarooWrapper\Validate\Billink;
use Buckaroo\BuckarooWrapper\Validate\BuckarooVoucher;
use Buckaroo\BuckarooWrapper\Validate\BuckarooWallet;
use Buckaroo\BuckarooWrapper\Validate\CreditCard;
use Buckaroo\BuckarooWrapper\Validate\CreditClick;
use Buckaroo\BuckarooWrapper\Validate\Emandates;
use Buckaroo\BuckarooWrapper\Validate\EPS;
use Buckaroo\BuckarooWrapper\Validate\GiftCard;
use Buckaroo\BuckarooWrapper\Validate\Giropay;
use Buckaroo\BuckarooWrapper\Validate\iDeal;
use Buckaroo\BuckarooWrapper\Validate\iDealQR;
use Buckaroo\BuckarooWrapper\Validate\iDin;
use Buckaroo\BuckarooWrapper\Validate\In3;
use Buckaroo\BuckarooWrapper\Validate\KBC;
use Buckaroo\BuckarooWrapper\Validate\KlarnaKP;
use Buckaroo\BuckarooWrapper\Validate\KlarnaPay;
use Buckaroo\BuckarooWrapper\Validate\Marketplaces;
use Buckaroo\BuckarooWrapper\Validate\Payconiq;
use Buckaroo\BuckarooWrapper\Validate\Paypal;
use Buckaroo\BuckarooWrapper\Validate\PayPerEmail;
use Buckaroo\BuckarooWrapper\Validate\PointOfSale;
use Buckaroo\BuckarooWrapper\Validate\Przelewy24;
use Buckaroo\BuckarooWrapper\Validate\RequestToPay;
use Buckaroo\BuckarooWrapper\Validate\SEPA;
use Buckaroo\BuckarooWrapper\Validate\Sofort;
use Buckaroo\BuckarooWrapper\Validate\Subscriptions;
use Buckaroo\BuckarooWrapper\Validate\Surepay;
use Buckaroo\BuckarooWrapper\Validate\Tinka;
use Buckaroo\BuckarooWrapper\Validate\Trustly;
use Buckaroo\BuckarooWrapper\Validate\WeChatPay;
use Buckaroo\BuckarooWrapper\Validate\CreditManagement;

trait DataValidator
{

    public function validateInput(string $payementType, string $method, array $data)
    {
        foreach (self::$payments as $class => $values) {
            if (in_array($payementType, $values)) {
                return method_exists($class, $method) ? $class::$method($data) : false;
            }
        }

    }

    private static array $payments = [
        ApplePay::class => ['applepay'],
        Alipay::class => ['alipay'],
        Afterpay::class => ['afterpay'],
        AfterpayDigiAccept::class => ['afterpaydigiaccept'],
        Bancontact::class => ['bancontactmrcash'],
        Billink::class => ['billink'],
        Belfius::class => ['belfius'],
        BuckarooWallet::class => ['buckaroo_wallet'],
        CreditCard::class => [
            'creditcard', 'mastercard', 'visa', 'amex',
            'vpay', 'maestro', 'visaelectron', 'cartebleuevisa',
            'cartebancaire', 'dankort', 'nexi', 'postepay'
        ],
        CreditClick::class => ['creditclick'],
        CreditManagement::class => ['credit_management'],
        iDeal::class => ['ideal', 'idealprocessing'],
        iDealQR::class => ['ideal_qr'],
        iDin::class => ['idin'],
        In3::class => ['in3'],
        KlarnaPay::class => ['klarna', 'klarnain'],
        KlarnaKP::class => ['klarnakp'],
        Surepay::class => ['surepay'],
        Subscriptions::class => ['subscriptions'],
        SEPA::class => ['sepadirectdebit', 'sepa'],
        KBC::class => ['kbcpaymentbutton'],
        Paypal::class => ['paypal'],
        PayPerEmail::class => ['payperemail'],
        EPS::class => ['eps'],
        Emandates::class => ['emandates'],
        Sofort::class => ['sofort', 'sofortueberweisung'],
        Tinka::class => ['tinka'],
        Marketplaces::class => ['marketplaces'],
        Payconiq::class => ['payconiq'],
        Przelewy24::class => ['przelewy24'],
        PointOfSale::class => ['pospayment'],
        Giropay::class => ['giropay'],
        GiftCard::class => [
            'giftcard', 'westlandbon', 'ideal', 'ippies',
            'babygiftcard', 'babyparkgiftcard', 'beautywellness',
            'boekenbon', 'boekenvoordeel', 'designshopsgiftcard',
            'fashioncheque', 'fashionucadeaukaart', 'fijncadeau',
            'koffiecadeau', 'kokenzo', 'kookcadeau', 'nationaleentertainmentcard',
            'naturesgift', 'podiumcadeaukaart', 'shoesaccessories', 'webshopgiftcard',
            'wijncadeau', 'wonenzo', 'yourgift', 'vvvgiftcard', 'parfumcadeaukaart'
        ],
        Trustly::class => ['trustly'],
        BankTransfer::class => ['transfer'],
        RequestToPay::class => ['requesttopay'],
        WeChatPay::class => ['wechatpay'],
        BuckarooVoucher::class => ['buckaroovoucher'],
    ];

}
