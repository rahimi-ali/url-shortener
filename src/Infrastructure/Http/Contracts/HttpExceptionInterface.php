<?php

namespace Infrastructure\Http\Contracts;

use Psr\Http\Message\ResponseInterface;
use Throwable;

interface HttpExceptionInterface extends Throwable
{
    public function render(RequestInterface $request): ResponseInterface;
}