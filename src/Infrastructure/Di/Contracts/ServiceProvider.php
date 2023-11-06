<?php

namespace Infrastructure\Di\Contracts;

interface ServiceProvider
{
    public function register(ServiceContainerInterface $serviceContainer): void;
}