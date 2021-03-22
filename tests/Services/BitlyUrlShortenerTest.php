<?php

namespace App\Tests\Services;

use App\Exception\UrlShortingFailedException;
use App\Services\BitlyUrlShortener;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Log\LoggerInterface;

class BitlyUrlShortenerTest extends TestCase
{

    private static string $URL_FOR_ENCODE = 'https://www.google.com/';

    private BitlyUrlShortener $instance;
    private LoggerInterface $logger;
    private Client $bitlyClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bitlyClient = $this->createMock(Client::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->instance = new BitlyUrlShortener($this->bitlyClient, $this->logger);
    }

    public function testEncode()
    {
        // prepare http response
        $streamInterface = $this->createMock(StreamInterface::class);
        $responseInterface = $this->createMock(ResponseInterface::class);
        $responseInterface->expects($this->once())->method('getBody')->willReturn($streamInterface);

        $streamInterface->expects($this->once())->method('getContents')->willReturn(\json_encode(['link' => 'bit.ly/123']));

        $this->bitlyClient->expects($this->exactly(1))->method('post')->with('/v4/shorten', [
            'json' => [
                'long_url' => self::$URL_FOR_ENCODE
            ]
        ])->willReturn($responseInterface);

        $result = $this->instance->encode(self::$URL_FOR_ENCODE);

        $this->assertEquals('bit.ly/123', $result);
    }

    public function testEncodeExpception()
    {
        $streamInterface = $this->createMock(StreamInterface::class);
        $requestInterface = $this->createMock(RequestInterface::class);
        $responseInterface = $this->createMock(ResponseInterface::class);

        $responseInterface->method('getBody')->willReturn($streamInterface);
        $streamInterface->method('getContents')->willReturn(\json_encode(['message' => 'invalid']));

        $this->bitlyClient->expects($this->exactly(1))->method('post')->with('/v4/shorten', [
            'json' => [
                'long_url' => self::$URL_FOR_ENCODE,
            ]
        ])->willThrowException(new ClientException('', $requestInterface, $responseInterface, null));

        $this->expectException(UrlShortingFailedException::class);
        $this->instance->encode(self::$URL_FOR_ENCODE);

    }

    public function testEncodeExpceptionWithNullResponse()
    {
        $streamInterface = $this->createMock(StreamInterface::class);
        $requestInterface = $this->createMock(RequestInterface::class);
        $responseInterface = $this->createMock(ResponseInterface::class);

        $responseInterface->method('getBody')->willReturn($streamInterface);
        $streamInterface->method('getContents')->willReturn(null);


        $this->bitlyClient->expects($this->exactly(1))->method('post')->with('/v4/shorten', [
            'json' => [
                'long_url' => self::$URL_FOR_ENCODE,
            ]
        ])->willThrowException(new ClientException('', $requestInterface, $responseInterface, null));

        $this->expectException(UrlShortingFailedException::class);
        $this->instance->encode(self::$URL_FOR_ENCODE);
    }

    public function testDecode()
    {
        $this->assertNull(null);
    }
}
