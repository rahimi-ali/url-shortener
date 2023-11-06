<?php

namespace App\ServiceProviders;

use Infrastructure\Di\Contracts\ServiceContainerInterface;
use Infrastructure\Di\Contracts\ServiceProvider;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LoggingServiceProvider implements ServiceProvider
{
    public function register(ServiceContainerInterface $serviceContainer): void
    {
        $logger = new Logger('logger');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../../logs/app.log'));
        $serviceContainer->set(LoggerInterface::class, $logger);
    }
}