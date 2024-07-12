<?php

namespace Buckaroo\Laravel\PaymentMethods;

use BadMethodCallException;
use Illuminate\Contracts\Support\Arrayable;
use Str;

/**
 * @method static string getCurrency()
 * @method static float getAmountDebit()
 * @method static string getDescription()
 * @method static string getOrder()
 * @method static string getInvoice()
 * @method static string getReturnURL()
 * @method static string getPushURL()
 * @method static string getClientIP()
 * @method static static setCurrency(string $currency)
 * @method static static setAmountDebit(float $amountDebit)
 * @method static static setDescription(string $description)
 * @method static static setOrder(string $order)
 * @method static static setInvoice(string $invoice)
 * @method static static setReturnURL(string $returnURL)
 * @method static static setPushURL(string $pushURL)
 * @method static static setClientIP(string $clientIP)
 */
class PaymentGatewayHandler implements Arrayable
{
    protected ?string $serviceCode;
    protected array $payload = [];
    protected bool $shouldAuthorize = false;

    public function __construct(?string $serviceCode = null)
    {
        if ($serviceCode) {
            $this->serviceCode = $serviceCode;
        }
    }

    public static function make(?string $serviceCode = null): static
    {
        return new static($serviceCode);
    }

    public function shouldAuthorize(bool $shouldAuthorize = true): static
    {
        $this->shouldAuthorize = $shouldAuthorize;

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, 'set')) {
            $property = Str::of($name)->after('set')->camel()->toString();
            $this->payload[$property] = $arguments[0];

            return $this;
        } elseif (Str::startsWith($name, 'get')) {
            $property = Str::of($name)->after('get')->camel()->toString();

            return data_get($this->payload, $property);
        }

        throw new BadMethodCallException("Method {$name} does not exist.");
    }

    public function getServiceCode(): ?string
    {
        return $this->serviceCode;
    }

    public function getPayAction(): ?string
    {
        return 'pay';
    }

    public function getRefundAction(): ?string
    {
        return 'refund';
    }

    public function setPayload(array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'returnURL' => route('buckaroo.return'),
                'pushURL' => route('buckaroo.push'),
            ],
            $this->payload,
        );
    }
}
