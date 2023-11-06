<?php

namespace App\Exceptions;

use Exception;
use Infrastructure\Http\Contracts\HttpExceptionInterface;
use Infrastructure\Http\Contracts\RequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class IncorrectCredentialsException extends Exception implements HttpExceptionInterface
{
    public function render(RequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            [
                'message' => 'Incorrect Credentials.'
            ],
            401
        );
    }
}