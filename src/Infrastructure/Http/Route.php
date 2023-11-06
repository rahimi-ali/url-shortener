<?php

namespace Infrastructure\Http;

use Infrastructure\Http\Contracts\HttpMethod;
use Infrastructure\Http\Contracts\RouteInterface;

class Route implements RouteInterface
{
    private mixed $handler;

    /** @param callable[] $middleware */
    public function __construct(
        private string $path,
        private HttpMethod $method,
        callable|array $handler,
        private array $middleware = [],
    ) {
        $this->handler = $handler;
    }

    public function match(string $requestPath, HttpMethod $requestMethod): array|false
    {
        if ($requestMethod !== $this->method) {
            return false;
        }

        $normalizedPathRegex = $this->normalizePathRegex($this->path);

        if (preg_match_all($normalizedPathRegex, $requestPath, $matches)) {
            // only string keys of matched regex are considered route params
            return array_map(
                fn ($match) => $match[0],
                array_filter($matches, fn(string $key) => !is_numeric($key), ARRAY_FILTER_USE_KEY),
            );
        }

        return false;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    public function getHandler(): callable|array
    {
        return $this->handler;
    }

    public function addMiddleware(callable|array $middleware): self
    {
        $this->middleware[] = $middleware;
        return $this;
    }

    public function getMiddleware(): array
    {
        return $this->middleware;
    }

    private function normalizePathRegex(string $path): string
    {
        return '/' . $this->escapeSlashes(
            $this->allowStartingAndEndingSlash(
                $this->convertParamShortcutsToRegex(
                    $path
                )
            )
        ) . '$/';
    }

    private function convertParamShortcutsToRegex(string $path): string
    {
        // changes anything in the form of :xyz to (?<xyz>\w+) which is a named regex group accepting any letter
        return preg_replace('/(:(\w+))/', '(?<$2>\w+)', $path);
    }

    private function allowStartingAndEndingSlash(string $path): string
    {
        return '\/' . trim($path, '/') . '\/?';
    }

    private function escapeSlashes(string $path): string
    {
        return preg_replace('/(?<!\\\)\//', '\/', $path);
    }
}