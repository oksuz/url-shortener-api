<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionController
{

    public function showException(Request $request): Response
    {
        $exception = $request->get('exception');
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        if ($exception instanceof HttpException) {
            $status = $exception->getStatusCode();
        }

        return new JsonResponse(['message' => $exception->getMessage()], $status);
    }
}
