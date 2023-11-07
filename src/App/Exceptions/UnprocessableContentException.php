<?php

namespace App\Exceptions;

use Infrastructure\Http\Contracts\HttpExceptionInterface;
use Exception;
use Infrastructure\Http\Contracts\RequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class UnprocessableContentException extends Exception implements HttpExceptionInterface
{
    /** @param array<string, string> $errors */
    public function __construct(private readonly array $errors)
    {
        parent::__construct('Unprocessable content.');
    }

    public function render(RequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            [
                'message' => $this->getMessage(),
                'errors' => $this->errors,
            ]
        );
    }
}