<?php

namespace App\Exceptions;

use Infrastructure\Http\Contracts\HttpExceptionInterface;
use Infrastructure\Http\Contracts\RequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Exception;

class ForbiddenException extends Exception implements HttpExceptionInterface
{
    public function render(RequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            [
                'message' => 'Forbidden.'
            ],
            403
        );
    }
}