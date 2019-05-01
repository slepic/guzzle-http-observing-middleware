<?php


namespace Slepic\Tests\Guzzle\Http\ObservingMiddleware;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slepic\Guzzle\Http\ObservingMiddleware\ObservingMiddleware;
use Slepic\Http\Transfer\Observer\ObserverDelegateInterface;
use Slepic\Http\Transfer\Observer\ObserverInterface;

class ObservingMiddlewareTest extends TestCase
{
    /**
     * @var ObserverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $observer;

    /**
     * @var ObserverDelegateInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $delegate;

    /**
     * @var ObservingMiddleware
     */
    private $middleware;

    /**
     * @var ClientInterface
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->observer = $this->createMock(ObserverInterface::class);
        $this->delegate = $this->createMock(ObserverDelegateInterface::class);
        $this->middleware = new ObservingMiddleware($this->observer);

        $this->client = new Client();
        $this->client->getConfig('handler')->unshift($this->middleware);
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @dataProvider provideSuccessTransfers
     */
    public function testSuccess($method, $uri, array $options = [])
    {
        $this->observer->expects($this->once())
            ->method('observe')
            ->willReturn($this->delegate);
        $this->delegate->expects($this->once())
            ->method('success');

        $response = $this->client->request($method, $uri, $options);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * @return array
     */
    public function provideSuccessTransfers()
    {
        $defaultOptions = [\md5(\time()) => \md5(\time())];
        return [
            [
                'GET',
                'http://www.example.com',
                $defaultOptions,
            ]
        ];
    }

    /**
     * @param $method
     * @param $uri
     * @param array $options
     * @dataProvider provideErrorTransfers
     */
    public function testError($method, $uri, array $options = [])
    {
        $this->observer->expects($this->once())
            ->method('observe')
            ->willReturn($this->delegate);
        $this->delegate->expects($this->once())
            ->method('error');

        $this->expectException(ClientException::class);
        $this->client->request($method, $uri, $options);
    }

    /**
     * @return array
     */
    public function provideErrorTransfers()
    {
        $defaultOptions = [\md5(\time()) => \md5(\time())];
        return [
            [
                'GET',
                'http://www.example.com/nonexistent',
                $defaultOptions,
            ]
        ];
    }
}
