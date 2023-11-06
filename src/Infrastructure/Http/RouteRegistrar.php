<?php

namespace Infrastructure\Http;

use Infrastructure\Http\Contracts\HttpMethod;
use Infrastructure\Http\Contracts\RouteInterface;
use Infrastructure\Http\Contracts\RouteRegistrarInterface;

class RouteRegistrar implements RouteRegistrarInterface
{
    /** @var RouteInterface[] */
    private array $routes;

    public function get(string $path, callable|array $handler): Route
    {
        $route = new Route($path, HttpMethod::Get, $handler);
        $this->routes[] = $route;
        return $route;
    }

    public function head(string $path, callable|array $handler): Route
    {
        $route = new Route($path, HttpMethod::Head, $handler);
        $this->routes[] = $route;
        return $route;
    }

    public function post(string $path, callable|array $handler): Route
    {
        $route = new Route($path, HttpMethod::Post, $handler);
        $this->routes[] = $route;
        return $route;
    }

    public function put(string $path, callable|array $handler): Route
    {
        $route = new Route($path, HttpMethod::Put, $handler);
        $this->routes[] = $route;
        return $route;
    }

    public function patch(string $path, callable|array $handler): Route
    {
        $route = new Route($path, HttpMethod::Patch, $handler);
        $this->routes[] = $route;
        return $route;
    }

    public function delete(string $path, callable|array $handler): Route
    {
        $route = new Route($path, HttpMethod::Delete, $handler);
        $this->routes[] = $route;
        return $route;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}