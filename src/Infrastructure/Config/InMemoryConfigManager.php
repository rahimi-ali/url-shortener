<?php

namespace Infrastructure\Config;

use Infrastructure\Config\Contracts\ConfigManagerInterface;

class InMemoryConfigManager implements ConfigManagerInterface
{
    private array $config = [];

    public function set(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    public function setMany(array $config): void
    {
        $this->config = array_merge($this->config, $config);
    }
}