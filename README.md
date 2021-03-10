[![Build Status](https://travis-ci.org/slepic/guzzle-http-observing-middleware.svg?branch=master)](https://travis-ci.org/slepic/guzzle-http-observing-middleware)
[![Style Status](https://styleci.io/repos/184423340/shield)](https://styleci.io/repos/184423340)

# guzzle-http-observing-middleware
Adapter of [ObserverInterface](https://github.com/slepic/http-transfer/blob/master/src/Observer/ObserverInterface.php) from [slepic/http-transfer](https://packagist.org/packages/slepic/http-transfer) package to [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle) middleware.

[packagist](https://packagist.org/packages/slepic/guzzle-http-observing-middleware)

## Requirements 

PHP >=5.6

## Installation

Install with composer.

```composer require slepic/guzzle-http-observing-middleware```

## Usage

Wrap any instance of [```\Slepic\Http\Transfer\Observer\ObserverInterface```](https://github.com/slepic/http-transfer/blob/master/src/Observer/ObserverInterface.php) from package [```slepic/http-transfer```](https://packagist.org/packages/slepic/http-transfer) in the [```\Slepic\Guzzle\Http\ObservingMiddleware\ObservingMiddleware```](https://github.com/slepic/guzzle-http-observing-middleware/blob/master/src/ObservingMiddleware.php) and pass it to handler stack of your guzzle client.

All requests sent through the guzzle client will now be notified when requests are starting to get processed and when responses are received.

See an example where we use [```\Slepic\Http\Transfer\History\HistoryObserver```](https://github.com/slepic/http-transfer/blob/master/src/History/HistoryObserver.php) to log requests and responses with timing.


```
$storage = new ArrayStorage();
$observer = new HistoryObserver($storage);
$middleware = new ObservingMiddleware($observer);

$client = new \GuzzleHttp\Client();
$client->getConfig('handler')->unshift($middleware);

try {
$response = $client->request($method, $uri);
} catch (\Exception $e) {
  assert($storage[0]->getRequest()->getMethod() === $method);
  assert((string)($storage[0]->getRequest()->getUri()) === $uri);
  assert($storage[0]->getException() === $e);
  assert(0 < ($storage[0]->getEndTime() - $storage[0]->getStartTime()));
  throw $e;
}

assert($storage[0]->getRequest()->getMethod() === 'GET');
assert((string)($storage[0]->getRequest()->getUri()) === $uri);
assert($storage[0]->getResponse() === $response);
assert(0 < ($storage[0]->getEndTime() - $storage[0]->getStartTime()));
```

## Related

* See [slepic/http-transfer-observer-implementation](https://packagist.org/providers/slepic/http-transfer-observer-implementation) for known observers.
* See [slepic/psr-http-message-tracy-panel](https://packagist.org/packages/slepic/psr-http-message-tracy-panel) to get your http client transfer into [Tracy](https://tracy.nette.org/en/).
