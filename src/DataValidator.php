<?php

namespace Buckaroo\Laravel;

use Buckaroo\Laravel\Validate\AfterpayDigiAccept;
use Buckaroo\Laravel\Validate\Afterpay;
use Buckaroo\Laravel\Validate\Alipay;
use Buckaroo\Laravel\Validate\ApplePay;
use Buckaroo\Laravel\Validate\Bancontact;
use Buckaroo\Laravel\Validate\BankTransfer;
use Buckaroo\Laravel\Validate\Belfius;
use Buckaroo\Laravel\Validate\Billink;
use Buckaroo\Laravel\Validate\BuckarooVoucher;
use Buckaroo\Laravel\Validate\BuckarooWallet;
use Buckaroo\Laravel\Validate\CreditCard;
use Buckaroo\Laravel\Validate\CreditClick;
use Buckaroo\Laravel\Validate\Emandates;
use Buckaroo\Laravel\Validate\EPS;
use Buckaroo\Laravel\Validate\GiftCard;
use Buckaroo\Laravel\Validate\Giropay;
use Buckaroo\Laravel\Validate\iDeal;
use Buckaroo\Laravel\Validate\iDealQR;
use Buckaroo\Laravel\Validate\iDin;
use Buckaroo\Laravel\Validate\In3;
use Buckaroo\Laravel\Validate\KBC;
use Buckaroo\Laravel\Validate\KlarnaKP;
use Buckaroo\Laravel\Validate\KlarnaPay;
use Buckaroo\Laravel\Validate\Marketplaces;
use Buckaroo\Laravel\Validate\Payconiq;
use Buckaroo\Laravel\Validate\Paypal;
use Buckaroo\Laravel\Validate\PayPerEmail;
use Buckaroo\Laravel\Validate\PointOfSale;
use Buckaroo\Laravel\Validate\Przelewy24;
use Buckaroo\Laravel\Validate\RequestToPay;
use Buckaroo\Laravel\Validate\SEPA;
use Buckaroo\Laravel\Validate\Sofort;
use Buckaroo\Laravel\Validate\Subscriptions;
use Buckaroo\Laravel\Validate\Surepay;
use Buckaroo\Laravel\Validate\Tinka;
use Buckaroo\Laravel\Validate\Trustly;
use Buckaroo\Laravel\Validate\WeChatPay;
use Buckaroo\Laravel\Validate\CreditManagement;

trait DataValidator
{

    public function validateInput(string $payementType, string $method, array $data = null)
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
