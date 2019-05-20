# Simple SES

[![Latest Stable Version](https://img.shields.io/packagist/v/magroski/simple-ses.svg?style=flat)](https://packagist.org/packages/magroski/simple-ses)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.1-8892BF.svg?style=flat)](https://php.net/)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat)](https://github.com/magroski/simple-ses/blob/master/LICENSE)

This library provides a quick and simple way to send emails using Amazon SES.

## Usage examples

```php
$config = new Config('access_key', 'secret_key', 'ue-east-1');

$client = new Client($config);

# Single Recipient
$client->send('Subject', 'Body', 'recipient@cool.com');

# Multiple Recipients
$client->send('Subject', 'Body', ['recipient@cool.com', 'another@email.com']);

# Text instead of html
$client->send('Subject', 'Body', 'recipient@nice.com', true);
```
