<?php

namespace Infrastructure\Http\Contracts;

interface RouteRegistrarInterface
{
    public function get(string $path, callable|array $handler): RouteInterface;

    public function head(string $path, callable|array $handler): RouteInterface;

    public function post(string $path, callable|array $handler): RouteInterface;

    public function put(string $path, callable|array $handler): RouteInterface;

    public function patch(string $path, callable|array $handler): RouteInterface;

    public function delete(string $path, callable|array $handler): RouteInterface;

    /** @return RouteInterface[] */
    public function getRoutes(): array;
}