<?php


namespace Slepic\Guzzle\Http\ObservingMiddleware;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Create;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slepic\Http\Transfer\Observer\ObserverInterface;

class ObservingMiddleware
{
    /**
     * @var ObserverInterface
     */
    private $observer;

    /**
     * ObservingMiddleware constructor.
     * @param ObserverInterface $observer
     */
    public function __construct(ObserverInterface $observer)
    {
        $this->observer = $observer;
    }

    /**
     * @param callable $handler
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            $delegate = $this->observer->observe($request, $options);
            return $handler($request, $options)->then(
                function (ResponseInterface $response) use ($request, $delegate) {
                    $delegate->success($response);
                    return $response;
                },
                function (\Exception $exception) use ($request, $delegate) {
                    $response = $exception instanceof RequestException
                        ? $exception->getResponse()
                        : null;
                    $delegate->error($exception, $response);
                    return Create::rejectionFor($exception);
                }
            );
        };
    }
}
