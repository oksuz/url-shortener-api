<?php


namespace App\Controller;

use App\Services\BijectiveUrlShortener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController
{
    private BijectiveUrlShortener $bijectiveUrlShorter;

    /**
     * RedirectController constructor.
     * @param BijectiveUrlShortener $bijectiveUrlShorter
     */
    public function __construct(BijectiveUrlShortener $bijectiveUrlShorter)
    {
        $this->bijectiveUrlShorter = $bijectiveUrlShorter;
    }

    /**
     * @Route("/{shortUrl}", methods={"GET"}, name="redirector", requirements={"shortUrl"="^[A-Za-z0-9_]+"})
     * @param string $shortUrl
     * @return JsonResponse
     */
    public function index(string $shortUrl): Response
    {
        $url = $this->bijectiveUrlShorter->decode($shortUrl);
        if ($url !== null) {
            return new RedirectResponse($url, Response::HTTP_FOUND);
        }

        return new JsonResponse(['error' => 'Not found'], Response::HTTP_NOT_FOUND);
    }
}
