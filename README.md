
<p align="center">
  <img src="https://www.buckaroo.nl/media/3877/laravel-logo-github.png" width="250px" position="center">
</p>

# Laravel Buckaroo Payment Integration

<p align="center">
  <img src="https://www.buckaroo.nl/media/3878/laravel-example3.png" width="800px" position="center">
</p>

---
### Index
- [Introduction](#introduction)
- [Installation](#installation)
- [Composer Installation](#composer-installation)
- [Usage](#usage)
- [Validation](#validation)
- [Conclusion](#conclusion)
- [Contribute](#contribute)
- [Versioning](#versioning)
- [Additional information](#additional-information)
---

### Introduction
The documentation explains how to integrate Laravel validation with the Buckaroo PHP SDK in your application. Buckaroo is a Payment Service Provider used by many companies to securely handle payments, subscriptions, and invoices. The Buckaroo SDK is a modern, open-source library that makes it easier to integrate Buckaroo's services into PHP apps. The SDK also supports webhooks.
### Installation

To install the Laravel Buckaroo Wrapper, you can use Composer by running the following command:

+ A Buckaroo account ([Dutch](https://www.buckaroo.nl/start) or [English](https://www.buckaroo.eu/solutions/request-form))
+ PHP >= 7.4
+ Up-to-date OpenSSL (or other SSL/TLS toolkit)

### Composer Installation

By far the easiest way to install the Laravel Buckaroo client is to require it with [Composer](http://getcomposer.org/doc/00-intro.md).

    $ composer require buckaroo/laravel:^1.0

    {
        "require": {
            "buckaroo/laravel": "^1.0"
        }
    }

### Usage
Create and config the Buckaroo object. 
You can find your credentials in plaza  [WEBSITE_KEY](https://plaza.buckaroo.nl/Configuration/Website/Index/) and [SECRET_KEY](https://admin.buckaroo.nl/Configuration/Merchant/SecretKey)

In order to use the Laravel Buckaroo Wrapper in your application, you need to add the following parameters to your .env file:

```php
BPE_WEBSITE_KEY=
BPE_SECRET_KEY=
BPE_MODE=
```

You should replace the empty values with the appropriate keys provided by Buckaroo.

Add the service provider in your config/app.php file:
```php
Buckaroo\Laravel\BuckarooServiceProvider::class,
```
Add the class Aliases in your config/app.php file:
```php
  'Buckaroo' => Buckaroo\Laravel\Facades\Buckaroo::class
```
Then run the following command to publish the Buckaroo config file:
```php
php artisan vendor:publish --provider="Buckaroo\Laravel\BuckarooServiceProvider"
```
Buckaroo service provider loads its own database migrations, so remember to run the necessary migrations to update your database after installing the package.
```php
php artisan migrate
```

The $buckaroo object is the instance of the Buckaroo PHP SDK.
The method method is called with the argument "creditcard", which indicates that the payment method used is a credit card.

The pay method is called with an associative array as its argument, which contains the details of the payment.
<ul>
<li><b>name:</b> the name of the credit card payment method (e.g. "visa").</li>
<li><b>amountDebit:</b> the amount of the payment.</li>
<li><b>invoice:</b> the invoice number associated with the payment.</li>
<li><b>pushURL:</b> the URL to which Buckaroo will send a push notification when the payment is processed.</li>
</ul>
This is set to the result of the route method with the argument "buckaroo.push".
</br>This code initiates a payment using the credit card payment method with the specified details.
</ul>

```php
Buckaroo::api()->method('creditcard')->pay([
    'name'          => 'visa',
    'amountDebit'   => 10.25,
    'invoice'       => 'inv-123',
    'pushURL'      => route('buckaroo.push')
]);
```
Find our full documentation online on [dev.buckaroo.nl](https://dev.buckaroo.nl/).
### Validation

Laravel validation is used to ensure the data passed to the payment method is valid and secure. This validation checks the parameters passed to the payment method and confirms that they are correct and complete. This helps to prevent errors and guarantees that payments are processed accurately.

```php
use App\BuckarooLaravelWrapper\src\Http\Requests\Payments\CreditCard\CreditCardPayRequest;

    public function preparePayment(CreditCardPayRequest $request)
    {
        $response = \Buckaroo::api()->method('creditcard')->pay($request->all());

        return response($response->toArray());
    }
```

Behind the scenes, this will register a POST route to a controller provided by this package. Because the app that sends webhooks to you has no way of getting a csrf-token, you must add that route to the except array of the VerifyCsrfToken middleware:
```php
    protected $except = [
        'buckaroo/*',
    ];
```

### Conclusion

By following the steps outlined in this documentation, you can easily integrate Laravel validation with the Buckaroo PHP SDK in your application. This will allow you to securely process payments, subscriptions, and unpaid invoices with the Buckaroo platform. Remember to add the BPE_WEBSITE_KEY, BPE_SECRET_KEY, and BPE_MODE to your .env file and run
```php
php artisan vendor:publish --provider="Buckaroo\Laravel\BuckarooServiceProvider"
php artisan migrate
```
to make sure everything runs smoothly.

### Contribute

We really appreciate it when developers contribute to improve the Buckaroo plugins.
If you want to contribute as well, then please follow our [Contribution Guidelines](CONTRIBUTING.md).

### Versioning
<p align="left">
  <img src="https://user-images.githubusercontent.com/7081446/178474134-f4c3976d-653c-4ca1-bcd1-48bf6d489196.png" width="500px" position="center">
</p>

- **MAJOR:** Breaking changes that require additional testing/caution
- **MINOR:** Changes that should not have a big impact
- **PATCHES:** Bug and hotfixes only

### Additional information
- **Support:** https://support.buckaroo.eu/contact
- **Contact:** [support@buckaroo.nl](mailto:support@buckaroo.nl) or [+31 (0)30 711 50 50](tel:+310307115050)

## License
Laravel Buckaroo Wrapper is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
