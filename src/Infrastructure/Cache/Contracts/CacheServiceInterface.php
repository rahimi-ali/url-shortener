<?php

namespace Infrastructure\Cache\Contracts;

interface CacheServiceInterface
{
    public function remember(string $key, int|float|string $value, int $ttl);

    public function get(string $key): int|float|string|false;

    public function forget(string $key): void;
}