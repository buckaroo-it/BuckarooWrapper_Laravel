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
trait DataValidator
{

    public function validate(string $payementType, string $methodName, array $data)
    {

        $className = $this->getPaymentClass($payementType);

        if (!$className) {
            return false;
        }

        switch ($className) {
            case (method_exists($className, $methodName)) :
                return $className::$methodName($data);
            default:
                return false;
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
        CreditCard::class => ['creditcard', 'mastercard', 'visa', 'amex', 'vpay', 'maestro', 'visaelectron', 'cartebleuevisa', 'cartebancaire', 'dankort', 'nexi', 'postepay'],
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
        GiftCard::class => ['giftcard', 'westlandbon', 'ideal', 'ippies', 'babygiftcard', 'babyparkgiftcard', 'beautywellness', 'boekenbon', 'boekenvoordeel', 'designshopsgiftcard', 'fashioncheque', 'fashionucadeaukaart', 'fijncadeau', 'koffiecadeau', 'kokenzo', 'kookcadeau', 'nationaleentertainmentcard', 'naturesgift', 'podiumcadeaukaart', 'shoesaccessories', 'webshopgiftcard', 'wijncadeau', 'wonenzo', 'yourgift', 'vvvgiftcard', 'parfumcadeaukaart'],
        Trustly::class => ['trustly'],
        BankTransfer::class => ['transfer'],
        RequestToPay::class => ['requesttopay'],
        WeChatPay::class => ['wechatpay'],
        BuckarooVoucher::class => ['buckaroovoucher'],
    ];

    public function getPaymentClass(string $string)
    {
        foreach (self::$payments as $class => $values) {
            if (in_array($string, $values)) {
                return $class;
            }
        }
        return false;
    }
}
