<?php

namespace App\Controller;

use App\Services\UrlShortenerABRouter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UrlShortenerController
 * @package App\Controller
 * @Route(condition="(request.headers.get('x-api-version') == '1')")
 */
class UrlShortenerController extends AbstractController
{

    private UrlShortenerABRouter $urlShortenerAbRouter;

    /**
     * UrlShortenerController constructor.
     * @param UrlShortenerABRouter $urlShortenerAbRouter
     */
    public function __construct(UrlShortenerABRouter $urlShortenerAbRouter)
    {
        $this->urlShortenerAbRouter = $urlShortenerAbRouter;
    }

    /**
     * @Route("/shorten", methods={"POST"}, name="app_home")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function index(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $constraint = new Assert\Collection([
           'url' => [
               new Assert\NotBlank(null, 'url should not be blank'),
               new Assert\Url()
           ]
        ]);

        $body = \json_decode($request->getContent(), true);
        $violations = $validator->validate($body, $constraint);
        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = ['msg' => $violation->getMessage(), 'field' => $violation->getPropertyPath()];
            }

            return new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $url = $this->urlShortenerAbRouter->shortUrl($body['url']);
            return new JsonResponse(['url' => $url], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
