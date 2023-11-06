<?php

namespace Infrastructure\Config\Contracts;

interface ConfigManagerInterface
{
    public function set(string $key, mixed $value): void;

    public function get(string $key, mixed $default = null): mixed;

    /** @param array<string, mixed> $config */
    public function setMany(array $config): void;
}