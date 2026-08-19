<p align="center">
  <a href="https://www.buckaroo.nl">
    <img src="https://raw.githubusercontent.com/buckaroo-it/Media/main/Buckaroo/README.md%20Headers/buckaroo-laravel-wrapper-header-rounded.png" alt="Buckaroo — Laravel Wrapper" width="100%">
  </a>
</p>

<h1 align="center">Buckaroo for Laravel</h1>

<p align="center">
  <a href="https://packagist.org/packages/buckaroo/laravel"><img src="https://img.shields.io/packagist/v/buckaroo/laravel.svg?label=release" alt="Latest release"></a>
  <a href="https://packagist.org/packages/buckaroo/laravel"><img src="https://img.shields.io/packagist/php-v/buckaroo/laravel.svg?label=PHP" alt="PHP version"></a>
  <a href="https://github.com/buckaroo-it/BuckarooWrapper_Laravel/blob/main/LICENSE"><img src="https://img.shields.io/packagist/l/buckaroo/laravel.svg?label=license" alt="License"></a>
  <a href="https://docs.buckaroo.io/docs/laravel-wrapper"><img src="https://img.shields.io/badge/docs-docs.buckaroo.io-1a1a4b.svg" alt="Documentation"></a>
</p>

<p align="center">
  <a href="#about">About</a> &middot;
  <a href="#requirements">Requirements</a> &middot;
  <a href="#installation">Installation</a> &middot;
  <a href="#configuration">Configuration</a> &middot;
  <a href="#usage">Usage</a> &middot;
  <a href="#testing">Testing</a> &middot;
  <a href="#support">Support</a> &middot;
  <a href="#contribute">Contribute</a>
</p>

---

## About

This package integrates the Buckaroo payment gateway into a Laravel application. It wraps the [Buckaroo PHP SDK](https://github.com/buckaroo-it/BuckarooSdk_PHP) in Laravel conventions: a service provider, a facade, publishable config, migrations and routes for the return and push callbacks.

Use it to start payments and to handle refunds, captures and authorization cancellations. Every part is designed to be overridden, so you can swap the transaction model or define your own routes without forking the package.

If you run a shop on a supported e-commerce platform, use the ready-made plugin for [Magento 2](https://github.com/buckaroo-it/Magento2), [Shopware 6](https://github.com/buckaroo-it/Shopware6), [WooCommerce](https://github.com/buckaroo-it/WooCommerce) or [Odoo](https://github.com/buckaroo-it/Odoo) instead. This package is for custom Laravel applications.

[Full wrapper documentation on docs.buckaroo.io](https://docs.buckaroo.io/docs/laravel-wrapper). For request parameters and service codes, see the [API reference](https://docs.buckaroo.io/reference).

---

## Requirements

| Requirement | Supported versions |
|---|---|
| PHP | 8.0 or higher |
| Laravel | 9, 10, 11, 12 and 13 |
| Buckaroo PHP SDK | 1.10 or higher (installed automatically) |

You also need a Buckaroo account and an up-to-date SSL/TLS toolkit such as OpenSSL. Don't have an account yet? [Request an account](https://www.buckaroo.nl/start).

---

## Installation

Install the package with Composer:

```bash
composer require buckaroo/laravel
```

Publish the configuration, migrations and routes:

```bash
php artisan vendor:publish --provider="Buckaroo\Laravel\BuckarooServiceProvider"
```

Run the migrations to create the transaction table:

```bash
php artisan migrate
```

---

## Configuration

Add your credentials to `.env`. You can find both keys under [API credentials in Buckaroo Plaza](https://plaza.buckaroo.nl/Configuration/Merchant/ApiKeys).

```dotenv
BPE_WEBSITE_KEY=your_store_key
BPE_SECRET_KEY=your_secret_key
BPE_MODE=test
```

Set `BPE_MODE` to `test` while developing and to `live` in production. The client is initialised automatically during the application boot, so no further wiring is needed.

> [!NOTE]
> The Store key was previously called the Website key. The environment variable is still named `BPE_WEBSITE_KEY` for backwards compatibility.

### Excluding the push route from CSRF verification

Buckaroo cannot send a CSRF token with its push messages, so the package's routes must be excluded from CSRF verification.

On Laravel 11 and higher, add this to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        'buckaroo/*',
    ]);
})
```

On Laravel 9 and 10, add the path to the `$except` array in `app/Http/Middleware/VerifyCsrfToken.php`:

```php
protected $except = [
    'buckaroo/*',
];
```

If you changed the route prefix, use that prefix instead of `buckaroo`.

<details>
<summary>Overriding the transaction model</summary>

The package stores transactions using `Buckaroo\Laravel\Models\BuckarooTransaction`. Point `config/buckaroo.php` at your own model to extend it:

```php
'transaction_model' => YourCustomTransactionModel::class,
```

</details>

<details>
<summary>Customising the routes</summary>

The package registers routes for the return and push callbacks. Change the prefix, or turn them off and define your own, in `config/buckaroo.php`:

```php
'routes' => [
    'load' => env('BPE_LOAD_ROUTES', true),
    'prefix' => env('BPE_ROUTE_PATH', 'buckaroo'),
],
```

</details>

<details>
<summary>Initialising the client manually</summary>

```php
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Transaction\Config\DefaultConfig;

Buckaroo::api()->setBuckarooClient(
    new DefaultConfig(
        websiteKey: config('buckaroo.website_key'),
        secretKey: config('buckaroo.secret_key'),
        mode: config('buckaroo.mode'),
        returnURL: route('buckaroo.return'),
        pushURL: route('buckaroo.push'),
    )
);
```

</details>

---

## Usage

### Starting a payment

Use `PayService` with `PaymentMethodFactory`. Pass a payload array:

```php
use Buckaroo\Laravel\Api\PayService;
use Buckaroo\Laravel\Handlers\PaymentMethodFactory;

$paymentSessionService = PayService::make(
    PaymentMethodFactory::make('noservice')->setPayload([
        'currency' => 'EUR',
        'amountDebit' => 100,
        'order' => '000-ORD',
        'invoice' => '000-INV',
        'description' => 'This is a description',
        'continueOnIncomplete' => '1',
        'servicesSelectableByClient' => 'ideal,bancontactmrcash',
    ])
);
```

Or use the setter methods, which are equivalent:

```php
$paymentSessionService = PayService::make(
    PaymentMethodFactory::make('noservice')
        ->setCurrency('EUR')
        ->setAmountDebit(100)
        ->setOrder('000-ORD')
        ->setInvoice('000-INV')
        ->setDescription('This is a description')
        ->setContinueOnIncomplete('1')
        ->setServicesSelectableByClient('ideal,bancontactmrcash')
);
```

Passing `noservice` lets the customer pick a method from `servicesSelectableByClient`. Pass a service code such as `ideal` to start a payment with one specific method.

### Calling the API directly

For full control, address the wrapper directly:

```php
use Buckaroo\Laravel\Facades\Buckaroo;

$response = Buckaroo::api()->method('ideal')->pay([
    'currency' => 'EUR',
    'amountDebit' => 100,
    'order' => '000-ORD',
    'invoice' => '000-INV',
    'description' => 'Payment for Order 000-ORD',
]);
```

Replace `ideal` with any service code, and `pay` with the action you need, such as `refund`. Service codes for every payment method are listed in the [API reference](https://docs.buckaroo.io/reference).

### Other services

`RefundService`, `CaptureService` and `CancelAuthorizeService` follow the same pattern as `PayService`.

---

## Testing

```bash
composer install
./vendor/bin/phpunit
```

Code style is enforced with [Laravel Pint](https://laravel.com/docs/pint):

```bash
./vendor/bin/pint
```

---

## Support

Having trouble? Work through this list before reaching out:

1. Check the [wrapper documentation](https://docs.buckaroo.io/docs/laravel-wrapper).
2. Confirm you are on the [latest release](https://github.com/buckaroo-it/BuckarooWrapper_Laravel/releases).
3. Reproduce the issue with `BPE_MODE=test` and check your Laravel log.
4. Verify that your push URL is reachable from outside your network. Buckaroo sends push messages from fixed IP addresses and ports, so make sure these are on your allow list. See [push messages](https://docs.buckaroo.io/docs/integration-push-messages) for the current list.

Still stuck? Contact us and include your PHP version, Laravel version, package version, the relevant log lines and the transaction key.

- **Bug reports and feature requests:** [open an issue](https://github.com/buckaroo-it/BuckarooWrapper_Laravel/issues)
- **Technical support:** [support@buckaroo.nl](mailto:support@buckaroo.nl)
- **Phone:** +31 (0)30 711 50 50
- **Gateway status:** [status.buckaroo.io](https://status.buckaroo.io/)

---

## Contribute

We really appreciate it when developers help improve the Buckaroo wrappers. Please read our [Contribution Guidelines](https://github.com/buckaroo-it/BuckarooWrapper_Laravel/blob/main/CONTRIBUTING.md) before opening a pull request, and target the `main` branch.

Found a security issue? Please report it privately to [support@buckaroo.nl](mailto:support@buckaroo.nl) instead of opening a public issue.

---

## Versioning

We follow semantic versioning (`MAJOR.MINOR.PATCH`):

- **MAJOR** — breaking changes that require additional testing and caution.
- **MINOR** — new functionality with limited impact.
- **PATCH** — bug fixes and hotfixes only.

All changes are documented on the [releases page](https://github.com/buckaroo-it/BuckarooWrapper_Laravel/releases).

---

## License

This package is open source software licensed under the [MIT license](https://github.com/buckaroo-it/BuckarooWrapper_Laravel/blob/main/LICENSE).

---

<p align="center">
  <sub>Made with care by <a href="https://www.buckaroo.nl">Buckaroo</a>.<br>
  This document is subject to change; typos and language errors are possible.</sub>
</p>
