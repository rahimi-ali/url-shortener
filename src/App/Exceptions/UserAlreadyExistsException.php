<?php

namespace App\Exceptions;

use Infrastructure\Http\Contracts\HttpExceptionInterface;
use Infrastructure\Http\Contracts\RequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Exception;

class UserAlreadyExistsException extends Exception implements HttpExceptionInterface
{
    public function render(RequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            [
                'message' => 'Username already taken.'
            ]
        );
    }
}