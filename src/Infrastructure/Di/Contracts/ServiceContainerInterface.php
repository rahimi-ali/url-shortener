<?php

namespace Infrastructure\Di\Contracts;

interface ServiceContainerInterface
{
    public function set(string $id, mixed $value): void;

    public function has(string $id): bool;

    public function get(string $id): mixed;

    public function make(string $class, array $parameters): object;

    public function call(callable|array $callable, array $parameters): mixed;
}