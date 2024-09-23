<p align="center">
  <img src="https://www.buckaroo.nl/media/3877/laravel-logo-github.png" width="250px" alt="Laravel Logo">
</p>

# Laravel Buckaroo Payment Integration

<p align="center">
  <img src="https://www.buckaroo.nl/media/3878/laravel-example3.png" width="800px" alt="Laravel Buckaroo Integration Example">
</p>

---

## Table of Contents

- [Introduction](#introduction)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
  - [Step 1: Install the Package](#step-1-install-the-package)
  - [Step 2: Publish Configuration and Assets](#step-2-publish-configuration-and-assets)
  - [Step 3: Run Migrations](#step-3-run-migrations)
  - [Step 4: Obtain Your Website and Secret Keys](#step-4-obtain-your-website-and-secret-keys)
  - [Step 5: Configure Environment Variables](#step-5-configure-environment-variables)
- [Configuration](#configuration)
  - [Transaction Model Override](#transaction-model-override)
  - [Customizing Routes](#customizing-routes)
- [Usage](#usage)
  - [Initializing the Buckaroo Client](#initializing-the-buckaroo-client)
  - [Starting a Payment Transaction](#starting-a-payment-transaction)
    - [Using Payload Array](#using-payload-array)
    - [Using Setter Methods](#using-setter-methods)
  - [Direct Usage with Buckaroo Wrapper](#direct-usage-with-buckaroo-wrapper)
  - [Other Services](#other-services)
- [Additional Information](#additional-information)
- [Contributing](#contributing)
- [Versioning](#versioning)
- [Support](#support)
- [License](#license)

---

## Introduction

Welcome to the **Laravel Buckaroo Payment Integration** package! This package offers a seamless integration of Buckaroo payment services into your Laravel application, enabling you to handle payments, refunds, captures, and authorization cancellations effortlessly.

The package is designed to be highly customizable, allowing developers to override and extend functionalities based on their requirements, making it flexible and adaptable for various use cases.

---

## Prerequisites

Ensure you have the following requirements before proceeding:

- **PHP**: Version 8.0 or higher
- **Laravel Framework**: Compatible with your Laravel version
- **Buckaroo Account**:
  - [Dutch](https://www.buckaroo.nl/start)
  - [English](https://www.buckaroo.eu/solutions/request-form)
- **SSL/TLS Toolkit**: An updated OpenSSL or any other SSL/TLS toolkit

---

## Installation

Follow these steps to install and set up the Laravel Buckaroo Payment Integration package.

### Step 1: Install the Package

Use Composer to install the package:

~~~bash
composer require buckaroo/laravel
~~~

### Step 2: Publish Configuration and Assets

Publish the package's configuration and assets using Artisan:

~~~bash
php artisan vendor:publish --provider="Buckaroo\Laravel\BuckarooServiceProvider"
~~~

This command will create configuration, migration, and route files in your Laravel project.

### Step 3: Run Migrations

Execute the migrations to set up the required database tables:

~~~bash
php artisan migrate
~~~

### Step 4: Obtain Your Website and Secret Keys

To integrate Buckaroo, you’ll need your **Website Key** and **Secret Key**. Obtain these from your Buckaroo account:

- **Website Key**: [Retrieve Here](https://plaza.buckaroo.nl/Configuration/Website/Index/)
- **Secret Key**: [Retrieve Here](https://admin.buckaroo.nl/Configuration/Merchant/SecretKey)

### Step 5: Configure Environment Variables

Add the following environment variables to your `.env` file:

~~~env
BPE_WEBSITE_KEY=your_website_key
BPE_SECRET_KEY=your_secret_key
BPE_MODE=test or live
~~~

- **BPE_WEBSITE_KEY**: Replace `your_website_key` with your Website Key.
- **BPE_SECRET_KEY**: Replace `your_secret_key` with your Secret Key.
- **BPE_MODE**: Set to `test` for testing or `live` for production.

These settings allow the Buckaroo Client to initialize automatically during your application’s boot process.

---

## Configuration

The package offers a variety of configuration options to suit different use cases.

### Transaction Model Override

By default, the package uses the `BuckarooTransaction` model to handle transactions. However, if you want to override this with your custom model, you can configure it in `config/buckaroo.php`:

~~~php
'transaction_model' => YourCustomTransactionModel::class,
~~~

The default value is:

~~~php
'transaction_model' => Buckaroo\Laravel\Models\BuckarooTransaction::class,
~~~

This allows you to maintain control over transaction handling and extend the functionality as needed.

### Customizing Routes

The package provides predefined routes for handling payment operations. If you prefer to customize these routes, you can configure the following options in `config/buckaroo.php`:

~~~php
'routes' => [
    'load' => env('BPE_LOAD_ROUTES', true),
    'prefix' => env('BPE_ROUTE_PATH', 'buckaroo'),
],
~~~

- **`load`**: Set this to `false` to prevent the package from automatically loading routes if you intend to define them yourself.
- **`prefix`**: Change the prefix to customize the route paths (default is `buckaroo`).

By adjusting these settings, you have full control over the routing structure in your application.

---

## Usage

### Initializing the Buckaroo Client

The Buckaroo client can be initialized automatically using the `.env` variables or manually if needed:

~~~php
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
~~~

### Starting a Payment Transaction

You can initiate a payment transaction using the `PayService` and `PaymentMethodFactory`.

#### Using Payload Array

~~~php
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
~~~

#### Using Setter Methods

~~~php
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
~~~

### Direct Usage with Buckaroo Wrapper

You can interact directly with the Buckaroo API using the built-in wrapper for greater control and flexibility:

~~~php
use Buckaroo\Laravel\Facades\Buckaroo;

$response = Buckaroo::api()->method('{SERVICE_CODE}')->{ACTION}([
    'currency' => 'EUR',
    'amountDebit' => 100,
    'order' => '000-ORD',
    'invoice' => '000-INV',
    'description' => 'This is a description',
]);
~~~

- Replace `{SERVICE_CODE}` with the payment method/service code (e.g., 'ideal').
- Replace `{ACTION}` with the desired action (`pay`, `refund`, etc.).

Example for an iDEAL payment:

~~~php
$response = Buckaroo::api()->method('ideal')->pay([
    'currency' => 'EUR',
    'amountDebit' => 100,
    'order' => '000-ORD',
    'invoice' => '000-INV',
    'description' => 'Payment for Order 000-ORD',
]);
~~~

### Other Services

The package provides additional services with similar logic:

- **RefundService**
- **CaptureService**
- **CancelAuthorizeService**

These services follow the same structure as `PayService` and can be used similarly to manage various payment actions.

---

## Additional Information

- **Full Documentation:** Explore our documentation on [dev.buckaroo.nl](https://dev.buckaroo.nl/).

---

## Contributing

We welcome contributions! Please follow our [Contribution Guidelines](CONTRIBUTING.md) when contributing to the project.

---

## Versioning

<p align="left">
  <img src="https://user-images.githubusercontent.com/7081446/178474134-f4c3976d-653c-4ca1-bcd1-48bf6d489196.png" width="500px" alt="Versioning">
</p>

We use [Semantic Versioning](https://semver.org/):

- **MAJOR**: Breaking changes requiring caution
- **MINOR**: New features that do not affect backward compatibility
- **PATCHES**: Bug fixes and minor improvements

---

## Support

For support, reach out via:

- **Support Portal:** [Contact Support](https://support.buckaroo.eu/contact)
- **Email:** [support@buckaroo.nl](mailto:support@buckaroo.nl)
- **Phone:** [+31 (0)30 711 50 50](tel:+310307115050)

---

## License

Laravel Buckaroo Wrapper is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
