<?php

namespace Infrastructure\Http\Exceptions;

use Exception;
use Infrastructure\Http\Contracts\HttpExceptionInterface;
use Infrastructure\Http\Contracts\RequestInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

class HttpNotFoundException extends Exception implements HttpExceptionInterface
{
    public function render(RequestInterface $request): ResponseInterface
    {
        return new Response\JsonResponse(
            data: [
                'code' => 404,
                'message' => 'Not Found'
            ],
            status: 404,
        );
    }
}