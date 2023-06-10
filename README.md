# 4 Jawaly SMS 
**4 Jawaly SMS** is a Laravel package that provides a method to use 4jawaly API services, with a few simple lines of code.

[![downloads](https://badgen.net//packagist/dt/devhereco/4jawaly)](https://packagist.org/packages/devhereco/4jawaly)
[![stars](https://badgen.net/github/stars/devhereco/4jawaly-SMS-Package)](https://github.com/devhereco/4jawaly-SMS-Package)
[![contributors](https://badgen.net/github/contributors/devhereco/4jawaly-SMS-Package)](https://github.com/devhereco/4jawaly-SMS-Package)
[![releases](https://badgen.net/github/releases/devhereco/4jawaly-SMS-Package)](https://github.com/devhereco/4jawaly-SMS-Package)
[![issues](https://badgen.net/github/open-issues/devhereco/4jawaly-SMS-Package)](https://github.com/devhereco/4jawaly-SMS-Package)
[![latest-release](https://badgen.net/packagist/v/devhereco/4jawaly/latest)](https://packagist.org/packages/devhereco/4jawaly)

## Installation

### 1. Require with [Composer](https://getcomposer.org/)
```sh
- composer require guzzle/guzzle
- composer require devhereco/4jawaly
```

### 2. Add Service Provider (Laravel 5.4 and below)

Latest Laravel versions have auto dicovery and automatically add service provider - if you're using 5.4.x and below, remember to add it to `providers` array at `/app/config/app.php`:

```php
// ...
Devhereco\4Jawaly\ServiceProvider::class,
```

### 3. Migrations

```sh
php artisan migrate
```

### 4. env variables

```sh
4JAWALY_SMS_USERNAME=
4JAWALY_SMS_PASSWORD=
4JAWALY_SMS_SENDER=
```

## Usages

### 1. One way messages
This function will allow you to send messages to selected numbers.

Examples:
```php
use Devhereco\4Jawaly\4Jawaly;

4Jawaly::send('966555644047', 'Test Message');
// 4Jawaly::send(Receiver Number, Message);
```

### 2. Get Account Balance
This function will show 4Jawaly account balance in your backend.

Examples:
```php
use Devhereco\4Jawaly\4Jawaly;

4Jawaly::balance();
```
