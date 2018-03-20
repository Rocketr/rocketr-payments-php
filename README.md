# Rocketr Payments API

### Composer
The easiest way to use the library is through [Composer](https://getcomposer.org/):

```shell
composer require rocketr/rocketr-payments-php
```

Then in your code, load the [Autoloader](https://getcomposer.org/doc/01-basic-usage.md#autoloading):
```php
require_once('vendor/autoload.php');
```

### Manual Installation

To manually use the library, download the [latest release](https://github.com/Rocketr/rocketr-payments-php/releases) and include the `init.php` file in your code:

```php
require_once('/path-to-rocketr-payments-php/init.php');
```

# Getting Started

Please take a look in the `examples/` folder to see how to use different API functions.

If you want to simply create a payment request, it's really simple:

```php
\RocketrPayments\RocketrPayments::setApiKey('API_CLIENT_ID', 'API_SECRET'); //From https://rocketr.net/merchants/api-keys
$o = new \RocketrPayments\Order();

$o->setPaymentMethod(\RocketrPayments\PaymentMethods::BitcoinPayment);
$o->setAmount(12.31);
$o->setBuyerEmail('saad@rocketr.net');
$o->addCustomField('internal_id', '2195342212');
$o->setIpnUrl('https://rocketr.net/webhook.php');

$result = $o->createOrder();

echo 'Please send ' . $result['paymentInstructions']['amount']  . $result['paymentInstructions']['currencyText'] . ' to ' . $result['paymentInstructions']['address'];
```

# Support

