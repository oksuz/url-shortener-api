<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * It allows to access resources any(*) cross origin
 * It will be configurable if it's needed
 *
 * Class CorsListener
 * @package App\EventListener
 *
 */
class CorsListener
{

    protected static array $CORS_HEADERS = [
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS, HEAD',
        'Access-Control-Request-Method' => 'OPTIONS',
        'Access-Control-Allow-Headers' => 'X-Requested-With, Origin, Content-Type, Accept, Authorization, X-api-version'
    ];

    public function onKernelRequest(RequestEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        if ('OPTIONS' === $request->getMethod() && $request->headers->has('Access-Control-Request-Method')) {
            $event->setResponse($this->allowCorsResponse());
            return;
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (HttpKernel::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }
        $request = $event->getRequest();

        if ($request->headers->has('Origin')) {
            $response = $event->getResponse();
            $response->headers->add(self::$CORS_HEADERS);
        }
    }

    protected function allowCorsResponse(): Response
    {
        $response = new Response();
        $response->headers->add(self::$CORS_HEADERS);
        $response->setStatusCode(204);
        return $response;
    }
}
