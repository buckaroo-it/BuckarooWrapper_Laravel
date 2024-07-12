<?php

namespace Buckaroo\Laravel\PaymentMethods\Afterpay;

use App\Services\Buckaroo\PaymentMethods\PaymentGatewayHandler;

/**
 * @method static static setBilling(array $billing)
 * @method static static setShipping(array $shipping)
 * @method static static setArticles(array $articles)
 * @method static array getBilling()
 * @method static array getShipping()
 * @method static array getArticles()
 */
class Afterpay extends PaymentGatewayHandler
{
    protected ?string $serviceCode = 'afterpay';

    public function getPayAction(): ?string
    {
        if ($this->shouldAuthorize) {
            return 'authorize';
        }

        return parent::getPayAction();
    }
}
