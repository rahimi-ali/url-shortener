<?php

namespace Infrastructure\Di;

use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Infrastructure\Di\Contracts\ServiceContainerInterface;

class ServiceContainer implements ServiceContainerInterface
{
    private readonly Container $diContainer;

    public function __construct()
    {
        $this->diContainer = new Container();
    }

    public function set(string $id, mixed $value): void
    {
        $this->diContainer->set($id, $value);
    }

    public function has(string $id): bool
    {
        return $this->diContainer->has($id);
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function get(string $id): mixed
    {
        return $this->diContainer->get($id);
    }

    public function make(string $class, array $parameters): object
    {
        return $this->diContainer->make($class, $parameters);
    }

    public function call(callable|array $callable, array $parameters): mixed
    {
        return $this->diContainer->call($callable, $parameters);
    }
}