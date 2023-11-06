<?php

namespace Infrastructure\Cache;

use Infrastructure\Cache\Contracts\CacheServiceInterface;
use Redis;

class RedisCache implements CacheServiceInterface
{
    public function __construct(
        private readonly Redis $redis
    ) {
    }

    public function remember(string $key, float|int|string $value, int $ttl)
    {
        $this->redis->setex($key, $ttl, $value);
    }

    public function get(string $key): int|float|string|false
    {
        return $this->redis->get($key);
    }

    public function forget(string $key): void
    {
        $this->redis->del($key);
    }
}