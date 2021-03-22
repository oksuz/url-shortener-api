<?php


namespace App\Services;

use App\Exception\UrlShortingFailedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class BitlyUrlShortener implements ShortenerInterface
{
    /**
     * @var Client
     */
    private Client $bitlyClient;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $log;

    /**
     * BitlyUrlShortener constructor.
     * @param Client  $bitlyClient
     * @param LoggerInterface $logger
     */
    public function __construct(Client $bitlyClient, LoggerInterface $logger)
    {
        $this->bitlyClient = $bitlyClient;
        $this->log = $logger;
    }

    /**
     * @param string $decoded
     * @return string
     * @throws UrlShortingFailedException|GuzzleException
     */
    public function encode(string $decoded): string
    {
        try {
            $response = $this->bitlyClient->post('/v4/shorten', [
                'json' => [
                    'long_url' => $decoded
                ]
            ]);

            $data = \json_decode($response->getBody()->getContents(), true);
            return $data['link'];
        } catch (ClientException $e) {
            $response = $e->getResponse()->getBody()->getContents();
            $json = [];
            if ($response !== null && trim($response) !== '') {
                $json += \json_decode($e->getResponse()->getBody()->getContents(), true);
                $this->log->error('bitly shortener error ' . print_r($json, true));
            }

            throw new UrlShortingFailedException(
                'shorting failed due to ' . (!empty($json['message']) ? $json['message'] : '')
            );
        }
    }

    /**
     * @param string $encoded
     * @return string
     * @throws \Exception
     */
    public function decode(string $encoded): string
    {
        throw new \Exception('not implemented yet!');
    }
}
