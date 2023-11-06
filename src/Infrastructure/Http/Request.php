<?php

namespace Infrastructure\Http;

use Infrastructure\Http\Contracts\RequestInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements RequestInterface
{
    /** @param array<string, mixed> $routeParams */
    public function __construct(
        private readonly ServerRequestInterface $underlying,
        private readonly array $routeParams,
    ) {
    }

    public function routeParam(string $key, mixed $default = null): mixed
    {
        return $this->routeParams[$key] ?? $default;
    }

    public function getProtocolVersion(): string
    {
        return $this->underlying->getProtocolVersion();
    }

    public function withProtocolVersion(string $version): MessageInterface
    {
        return $this->underlying->withProtocolVersion($version);
    }

    public function getHeaders(): array
    {
        return $this->underlying->getHeaders();
    }

    public function hasHeader(string $name): bool
    {
        return $this->underlying->hasHeader($name);
    }

    public function getHeader(string $name): array
    {
        return $this->underlying->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->underlying->getHeaderLine($name);
    }

    public function withHeader(string $name, $value): MessageInterface
    {
        return $this->underlying->withHeader($name, $value);
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        return $this->underlying->withAddedHeader($name, $value);
    }

    public function withoutHeader(string $name): MessageInterface
    {
        return $this->underlying->withoutHeader($name);
    }

    public function getBody(): StreamInterface
    {
        return $this->underlying->getBody();
    }

    public function withBody(StreamInterface $body): MessageInterface
    {
        return $this->underlying->withBody($body);
    }

    public function getRequestTarget(): string
    {
        return $this->underlying->getRequestTarget();
    }

    public function withRequestTarget(string $requestTarget): \Psr\Http\Message\RequestInterface
    {
        return $this->underlying->withRequestTarget($requestTarget);
    }

    public function getMethod(): string
    {
        return $this->underlying->getMethod();
    }

    public function withMethod(string $method): \Psr\Http\Message\RequestInterface
    {
        return $this->underlying->withMethod($method);
    }

    public function getUri(): UriInterface
    {
        return $this->underlying->getUri();
    }

    public function withUri(UriInterface $uri, bool $preserveHost = false): \Psr\Http\Message\RequestInterface
    {
        return $this->underlying->withUri($uri, $preserveHost);
    }

    public function getServerParams(): array
    {
        return $this->underlying->getServerParams();
    }

    public function getCookieParams(): array
    {
        return $this->underlying->getCookieParams();
    }

    public function withCookieParams(array $cookies): ServerRequestInterface
    {
        return $this->underlying->withCookieParams($cookies);
    }

    public function getQueryParams(): array
    {
        return $this->underlying->getQueryParams();
    }

    public function withQueryParams(array $query): ServerRequestInterface
    {
        return $this->underlying->withQueryParams($query);
    }

    public function getUploadedFiles(): array
    {
        return $this->underlying->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
    {
        return $this->underlying->withUploadedFiles($uploadedFiles);
    }

    public function getParsedBody()
    {
        return $this->underlying->getParsedBody();
    }

    public function withParsedBody($data): ServerRequestInterface
    {
        return $this->underlying->withParsedBody();
    }

    public function getAttributes(): array
    {
        return $this->underlying->getAttributes();
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->underlying->getAttribute($name, $default);
    }

    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        return $this->underlying->withAttribute($name, $value);
    }

    public function withoutAttribute(string $name): ServerRequestInterface
    {
        return $this->underlying->withoutAttribute($name);
    }
}