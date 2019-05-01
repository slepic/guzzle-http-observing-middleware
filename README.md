[![Build Status](https://travis-ci.org/slepic/guzzle-http-observing-middleware.svg?branch=master)](https://travis-ci.org/slepic/guzzle-http-observing-middleware)
[![Style Status](https://styleci.io/repos/184423340/shield)](https://styleci.io/repos/184423340)

# guzzle-http-observing-middleware
Adapter of [ObserverInterface](https://github.com/slepic/http-transfer/blob/master/src/Observer/ObserverInterface.php) from [slepic/http-transfer](https://packagist.org/packages/slepic/http-transfer) package to [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle) middleware.

[packagist](https://packagist.org/packages/slepic/guzzle-http-observing-middleware)

## Requirements 

PHP 5.6 or 7

## Installation

Install with composer.

```composer require slepic/guzzle-http-observing-middleware```

## Usage

```
/** @var \Slepic\Http\Transfer\Observer\ObserverInterface $observer */
$middleware = new \Slepic\Guzzle\Http\ObservingMiddleware\ObservingMiddleware($observer);

$client = new \GuzzleHttp\Client();

$client->getConfig('handler')->unshift($middleware);
```

## Related

* See [slepic/http-transfer-observer-implementation](https://packagist.org/providers/slepic/http-transfer-observer-implementation) for known observers.
* See [slepic/psr-http-message-tracy-panel](https://packagist.org/packages/slepic/psr-http-message-tracy-panel) to get your http client transfer into [Tracy](https://tracy.nette.org/en/).
