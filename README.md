# Kairos

[![PHP from Packagist](https://img.shields.io/packagist/php-v/decodelabs/kairos?style=flat)](https://packagist.org/packages/decodelabs/kairos)
[![Latest Version](https://img.shields.io/packagist/v/decodelabs/kairos.svg?style=flat)](https://packagist.org/packages/decodelabs/kairos)
[![Total Downloads](https://img.shields.io/packagist/dt/decodelabs/kairos.svg?style=flat)](https://packagist.org/packages/decodelabs/kairos)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/decodelabs/kairos/integrate.yml?branch=develop)](https://github.com/decodelabs/kairos/actions/workflows/integrate.yml)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-44CC11.svg?longCache=true&style=flat)](https://github.com/phpstan/phpstan)
[![License](https://img.shields.io/packagist/l/decodelabs/kairos?style=flat)](https://packagist.org/packages/decodelabs/kairos)

### Time and date utilities

Kairos provides simple time and date tools for PHP.

---

## Installation

Install via Composer:

```bash
composer require decodelabs/kairos
```

## Usage

Currently, Kairos contains a simple `Timer` class that can be used to measure elapsed time in PHP scripts.

```php
use DecodeLabs\Kairos\Timer;

$timer = new Timer();
sleep(1);
$elapsed = $timer->time;
sleep(1);
$total = $timer->stop();
```

More coming soon.

## Licensing

Kairos is licensed under the MIT License. See [LICENSE](./LICENSE) for the full license text.
