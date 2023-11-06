<?php

namespace App\ServiceProviders;

use Infrastructure\Cache\Contracts\CacheServiceInterface;
use Infrastructure\Cache\RedisCache;
use Infrastructure\Config\Contracts\ConfigManagerInterface;
use Infrastructure\Di\Contracts\ServiceContainerInterface;
use Infrastructure\Di\Contracts\ServiceProvider;
use Redis;
use RedisException;

class CachingServiceProvider implements ServiceProvider
{
    /** @throws RedisException */
    public function register(ServiceContainerInterface $serviceContainer): void
    {
        /** @var ConfigManagerInterface $configManager */
        $configManager = $serviceContainer->get(ConfigManagerInterface::class);

        $redis = new Redis();
        $redis->connect(host: $configManager->get('redis.host'), port: $configManager->get('redis.port'));

        $serviceContainer->set(CacheServiceInterface::class, new RedisCache($redis));
    }
}