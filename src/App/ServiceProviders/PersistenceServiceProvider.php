<?php

namespace App\ServiceProviders;

use Infrastructure\Cache\Contracts\CacheServiceInterface;
use Infrastructure\Cache\RedisCache;
use Infrastructure\Config\Contracts\ConfigManagerInterface;
use Infrastructure\Di\Contracts\ServiceContainerInterface;
use Infrastructure\Di\Contracts\ServiceProvider;
use PDO;

class PersistenceServiceProvider implements ServiceProvider
{
    public function register(ServiceContainerInterface $serviceContainer): void
    {
        /** @var ConfigManagerInterface $configManager */
        $configManager = $serviceContainer->get(ConfigManagerInterface::class);

        $pdo = new PDO(
            $configManager->get('database.dsn'),
            $configManager->get('database.user'),
            $configManager->get('database.password')
        );

        $serviceContainer->set(PDO::class, $pdo);
    }
}