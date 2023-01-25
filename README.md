
<p align="center">
  <img src="https://www.buckaroo.nl/media/3870/laravel-logo.png" width="250px" position="center">
</p>

# Laravel Buckaroo Payment Integration

<p align="center">
  <img src="https://www.buckaroo.nl/media/3869/laravel-code.png" width="500px" position="center">
</p>

---
### Index
- [About](#about)
- [Requirements](#requirements)
- [Composer Installation](#composer-installation)
- [Requirements](#requirements)
- [Example](#example)
- [Contribute](#contribute)
- [Versioning](#versioning)
- [Additional information](#additional-information)
---

### Introduction

This documentation describes the process of integrating Laravel validation with the Buckaroo PHP SDK in your application. Buckaroo is a Payment Service Provider that is used by over 15,000 companies to securely process payments, subscriptions, and unpaid invoices. The Buckaroo SDK is a modern, open-source library that simplifies integration with Buckaroo's services in PHP applications.

### Installation

To install the Laravel Buckaroo Wrapper, you can use Composer by running the following command:

+ A Buckaroo account ([Dutch](https://www.buckaroo.nl/start) or [English](https://www.buckaroo.eu/solutions/request-form))
+ PHP >= 7.4
+ Up-to-date OpenSSL (or other SSL/TLS toolkit)

### Composer Installation

By far the easiest way to install the Laravel Buckaroo Wrapper client is to require it with [Composer](http://getcomposer.org/doc/00-intro.md).

    $ composer require buckaroo/buckaroo-wrapper:^1.0

    {
        "require": {
            "buckaroo/buckaroo-wrapper": "^1.0"
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
Buckaroo\BuckarooWrapper\BuckarooServiceProvider::class,
```
Then run the following command to publish the Buckaroo config file:
```php
php artisan vendor:publish --provider="Buckaroo\BuckarooWrapper\BuckarooServiceProvider"
```
To use the  Laravel Buckaroo Wrapper, you first need to create an instance of the Buckaroo class:
```php
use Buckaroo\BuckarooWrapper\Buckaroo;

$buckaroo = new Buckaroo();
```
You can then call the payment method on the Buckaroo object, passing in the desired payment method, action, and other parameters. For example, the following code initiates a credit card payment for an amount of 10.25 and a unique invoice number:
```php
$buckaroo->payment('creditcard', 'pay', [
    'name'          => 'visa',
    'amountDebit'   => 10.25,
    'invoice'       => uniqid()
]);
```
Find our full documentation online on [dev.buckaroo.nl](https://dev.buckaroo.nl/).
### Validation

To ensure that the data passed to the payment method is valid and secure, Laravel validation is used. This validation is based on the method used and ensures that the parameters passed to the payment method are correct and complete. This can help to prevent errors and ensure that payments are processed correctly.

### Conclusion

By following the steps outlined in this documentation, you can easily integrate Laravel validation with the Buckaroo PHP SDK in your application. This will allow you to securely process payments, subscriptions, and unpaid invoices with the Buckaroo platform. Remember to add the BPE_WEBSITE_KEY, BPE_SECRET_KEY, and BPE_MODE to your .env file and run
```php
php artisan vendor:publish --provider="Buckaroo\BuckarooWrapper\BuckarooServiceProvider"
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
