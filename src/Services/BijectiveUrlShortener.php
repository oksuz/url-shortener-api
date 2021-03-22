<?php


namespace App\Services;

use App\Entity\ShortUrl;
use App\Exception\UrlShortingFailedException;
use App\Provider\BijectiveConverter;
use App\Repository\ShortUrlRepository;
use Psr\Log\LoggerInterface;

class BijectiveUrlShortener implements ShortenerInterface
{

    private BijectiveConverter $bijectiveConverter;
    private ShortUrlRepository $shortUrlRepository;
    private LoggerInterface $logger;

    /**
     * BijectiveUrlShortener constructor.
     * @param BijectiveConverter $bijectiveConverter
     * @param ShortUrlRepository $shortUrlRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        BijectiveConverter $bijectiveConverter,
        ShortUrlRepository $shortUrlRepository,
        LoggerInterface $logger
    ) {
        $this->bijectiveConverter = $bijectiveConverter;
        $this->shortUrlRepository = $shortUrlRepository;
        $this->logger = $logger;
    }

    /**
     * @param string $decoded
     * @return string
     * @throws UrlShortingFailedException
     */
    public function encode(string $decoded): string
    {
        $shortUrl = $this->shortUrlRepository->findOneBy(['url' => $decoded]);
        if ($shortUrl === null) {
            try {
                $shortUrl = new ShortUrl();
                $shortUrl->setUrl($decoded);
                $shortUrl = $this->shortUrlRepository->save($shortUrl);
            } catch (\Exception $e) {
                $this->logger->error('error on persisting entity: ' . $e->getMessage());
                throw new UrlShortingFailedException($e->getMessage());
            }
        }

        return '/' . $this->bijectiveConverter->encode($shortUrl->getId());
    }

    /**
     * @param string $encoded
     * @return string
     */
    public function decode(string $encoded): ?string
    {
        $id = $this->bijectiveConverter->decode($encoded);
        $shorUrl = $this->shortUrlRepository->find($id);
        if ($shorUrl !== null) {
            return $shorUrl->getUrl();
        }

        return null;
    }
}
