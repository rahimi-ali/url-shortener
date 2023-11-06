<?php

namespace Infrastructure\Http\Contracts;

use Psr\Http\Message\ResponseInterface;

interface RouteInterface
{
    /** @return array<string, mixed>|false false if not matched array of route params if matched */
    public function match(string $requestPath, HttpMethod $requestMethod): array|false;

    public function getPath(): string;

    public function getMethod(): HttpMethod;

    /** @return (callable(RequestInterface $request): ResponseInterface)|array */
    public function getHandler(): callable|array;

    /** @param (callable(RequestInterface $request, callable(RequestInterface): ResponseInterface $next): ResponseInterface)|array $middleware */
    public function addMiddleware(callable|array $middleware): self;

    /** @return ((callable(RequestInterface $request, callable(RequestInterface): ResponseInterface $next): ResponseInterface)|array)[] */
    public function getMiddleware(): array;
}