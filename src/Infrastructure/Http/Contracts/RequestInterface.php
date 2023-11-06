<?php

namespace Infrastructure\Http\Contracts;

use Psr\Http\Message\ServerRequestInterface;

interface RequestInterface extends ServerRequestInterface
{
    public function routeParam(string $key, mixed $default = null): mixed;
}